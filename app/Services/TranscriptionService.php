<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TranscriptionService
{
    /**
     * Transcribe a call recording using various methods
     */
    public function transcribeRecording(string $recordingPath, string $recordingSid): array
    {
        // Try Twilio transcription first
        $twilioTranscription = $this->getTwilioTranscription($recordingSid);
        if ($twilioTranscription['transcription_text']) {
            return $twilioTranscription;
        }

        // Try external transcription service
        $externalTranscription = $this->transcribeWithExternalService($recordingPath);
        if ($externalTranscription['transcription_text']) {
            return $externalTranscription;
        }

        // Return empty result if no transcription available
        return [
            'transcription_sid' => null,
            'transcription_text' => null,
            'status' => 'not_available',
            'confidence' => null,
            'source' => 'none'
        ];
    }

    /**
     * Get transcription from Twilio (if available)
     */
    private function getTwilioTranscription(string $recordingSid): array
    {
        try {
            // This would require proper Twilio transcription API integration
            // For now, return empty as transcriptions might not be enabled
            return [
                'transcription_sid' => null,
                'transcription_text' => null,
                'status' => 'not_available',
                'confidence' => null,
                'source' => 'twilio'
            ];
        } catch (\Exception $e) {
            Log::error("Twilio transcription error: " . $e->getMessage());
            return [
                'transcription_sid' => null,
                'transcription_text' => null,
                'status' => 'error',
                'confidence' => null,
                'source' => 'twilio'
            ];
        }
    }

    /**
     * Transcribe using external service (OpenAI Whisper, Google Speech-to-Text, etc.)
     */
    private function transcribeWithExternalService(string $recordingPath): array
    {
        try {
            // Check if recording file exists
            if (!Storage::disk('recordings')->exists($recordingPath)) {
                Log::error("Recording file not found: " . $recordingPath);
                return [
                    'transcription_sid' => null,
                    'transcription_text' => null,
                    'status' => 'file_not_found',
                    'confidence' => null,
                    'source' => 'external'
                ];
            }

            // Get the full path to the recording
            $fullPath = Storage::disk('recordings')->path($recordingPath);
            
            // Try OpenAI Whisper API
            $whisperResult = $this->transcribeWithOpenAI($fullPath);
            if ($whisperResult['transcription_text']) {
                return $whisperResult;
            }

            // Try Google Speech-to-Text
            $googleResult = $this->transcribeWithGoogle($fullPath);
            if ($googleResult['transcription_text']) {
                return $googleResult;
            }

            Log::error("No transcription available for recording: " . $recordingPath);
            return [
                'transcription_sid' => null,
                'transcription_text' => null,
                'status' => 'no_service_available',
                'confidence' => null,
                'source' => 'external'
            ];

        } catch (\Exception $e) {
            Log::error("External transcription error: " . $e->getMessage());
            return [
                'transcription_sid' => null,
                'transcription_text' => null,
                'status' => 'error',
                'confidence' => null,
                'source' => 'external'
            ];
        }
    }

    /**
     * Transcribe using OpenAI Whisper API
     */
    private function transcribeWithOpenAI(string $filePath): array
    {
        $apiKey = config('services.ai.key');
        $endpoint = config('services.ai.endpoint');

        if (!$apiKey || !$endpoint) {
            Log::error("No API key or endpoint found for OpenAI transcription");
            return [
                'transcription_sid' => null,
                'transcription_text' => null,
                'status' => 'no_api_key',
                'confidence' => null,
                'source' => 'openai'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->attach(
                'file',
                file_get_contents($filePath),
                basename($filePath)
            )->post($endpoint . '/v1/audio/transcriptions', [
                'model' => 'whisper-1',
                'response_format' => 'json'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'transcription_sid' => uniqid('whisper_'),
                    'transcription_text' => $data['text'] ?? null,
                    'status' => 'completed',
                    'confidence' => null,
                    'source' => 'openai'
                ];
            }
        } catch (\Exception $e) {
            Log::error("OpenAI transcription error: " . $e->getMessage());
        }

        return [
            'transcription_sid' => null,
            'transcription_text' => null,
            'status' => 'error',
            'confidence' => null,
            'source' => 'openai'
        ];
    }

    /**
     * Transcribe using Google Speech-to-Text API
     */
    private function transcribeWithGoogle(string $filePath): array
    {
        $apiKey = config('services.google.speech_key');

        if (!$apiKey) {
            Log::error("No API key found for Google transcription");
            return [
                'transcription_sid' => null,
                'transcription_text' => null,
                'status' => 'no_api_key',
                'confidence' => null,
                'source' => 'google'
            ];
        }

        try {
            $audioContent = base64_encode(file_get_contents($filePath));
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://speech.googleapis.com/v1/speech:recognize?key=' . $apiKey, [
                'config' => [
                    'encoding' => 'MP3',
                    'sampleRateHertz' => 8000,
                    'languageCode' => 'en-US',
                    'enableAutomaticPunctuation' => true,
                ],
                'audio' => [
                    'content' => $audioContent
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $transcription = '';
                $confidence = 0;
                $count = 0;

                if (isset($data['results'])) {
                    foreach ($data['results'] as $result) {
                        if (isset($result['alternatives'][0])) {
                            $transcription .= $result['alternatives'][0]['transcript'] . ' ';
                            $confidence += $result['alternatives'][0]['confidence'] ?? 0;
                            $count++;
                        }
                    }
                }

                return [
                    'transcription_sid' => uniqid('google_'),
                    'transcription_text' => trim($transcription),
                    'status' => 'completed',
                    'confidence' => $count > 0 ? $confidence / $count : null,
                    'source' => 'google'
                ];
            }
        } catch (\Exception $e) {
            Log::error("Google transcription error: " . $e->getMessage());
        }

        return [
            'transcription_sid' => null,
            'transcription_text' => null,
            'status' => 'error',
            'confidence' => null,
            'source' => 'google'
        ];
    }

    /**
     * Batch transcribe multiple recordings
     */
    public function batchTranscribe(array $recordings): array
    {
        $results = [];
        
        foreach ($recordings as $recording) {
            $results[] = [
                'recording_sid' => $recording['recording_sid'],
                'transcription' => $this->transcribeRecording(
                    $recording['recording_filename'],
                    $recording['recording_sid']
                )
            ];
        }
        
        return $results;
    }
} 