<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Communication;
use App\Services\SentimentAnalysisService;
use App\Services\TranscriptionService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class FetchTwilioRecordings extends Command
{
    const INBOUND_CALL = 'inbound';
    const OUTBOUND_CALL = 'outbound';
    
    private $twilio_client;
    private $sentimentService;
    protected $signature = 'app:fetch-twilio-recordings {--analyze : Include sentiment analysis}';
    
    protected $description = 'Get recordings from Twilio';

    public function __construct(SentimentAnalysisService $sentimentService)
    {
        parent::__construct();
        $this->twilio_client = new Client(config('twilio.twilio.sid'), config('twilio.twilio.token'));
        $this->sentimentService = $sentimentService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $access_numbers = $this->getAccessNumbers();
        $includeAnalysis = $this->option('analyze');
        
        //get calls by date range
        $calls = $this->twilio_client->calls->read([
            "startTimeAfter" => new \DateTime("2025-06-16T00:00:00Z"),
            "startTimeBefore" => new \DateTime("2025-06-20T23:59:59Z"),
        ]);

        $filtered_calls = [];
        foreach ($calls as $call) {
            $recordings_details = $this->getRecordingDetails($call->sid);
            foreach ($recordings_details as $recording_detail) {
                
                $callData = array_merge($recording_detail, [
                    'type' => $this->getCallType($call->fromFormatted),
                    'from' => $call->from,
                    'from_formatted' => $call->fromFormatted,
                    'to' => $call->to,
                    'to_formatted' => $call->toFormatted,
                    'date_time' => Carbon::parse($call->dateCreated)->format('Y-m-d H:i:s'),
                    'duration' => $call->duration,
                    'call_sid' => $call->sid,
                    'status' => $call->status,
                ]);

                // Add sentiment analysis if requested
                if ($includeAnalysis) {
                    $analysis = $this->sentimentService->analyzeCallRecording(
                        $callData['transcriptions'] ?? '',
                        $callData['type']
                    );
                    
                    $callData = array_merge($callData, [
                        'sentiment' => $analysis['sentiment'],
                        'keywords' => $analysis['keywords'],
                        'summary' => $analysis['summary'],
                        'is_booked' => $analysis['is_booked'],
                    ]);
                }

                $filtered_calls[] = $callData;
            }
        }

        if ($filtered_calls) {
            Communication::upsert($filtered_calls, ['call_sid']);
            $message = "Recordings fetched successfully";
            if ($includeAnalysis) {
                $message .= " with sentiment analysis";
            }
            echo $message;
        } else {
            echo "No recordings found";
        }
    }

    private function getRecordingDetails(string $call_sid): array {
        $filtered_recordings = [];
        $recordings = $this->twilio_client->recordings->read(['callSid' => $call_sid]);
        if ($recordings) {
            foreach ($recordings as $recording) {
                $media_details = $this->donwloadRecording($recording->mediaUrl, $recording->sid);
                
                // Get transcription for this recording
                $transcription = $this->getTranscription($recording->sid);
                
                $filtered_recordings[] = array_merge($media_details, [
                    'recording_sid' => $recording->sid,
                    'transcription_sid' => $transcription['transcription_sid'] ?? null,
                    'transcriptions' => $transcription['transcription_text'] ?? null,
                ]);
            }
            return $filtered_recordings;
        }

        return [[
            'recording_url_twilio' => '',
            'recording_url_axocall' => '',
            'recording_filename' => '',
            'recording_sid' => '',
            'transcription_sid' => null,
            'transcriptions' => null,
        ]];
    }

    private function getTranscription(string $recording_sid): array {
        try {
            // For now, return empty transcription data
            // Twilio transcriptions might not be available for all recordings
            // This can be enhanced later with proper Twilio transcription API
            return [
                'transcription_sid' => null,
                'transcription_text' => null,
                'status' => null,
                'confidence' => null,
            ];
        } catch (\Exception $e) {
            echo "Error getting transcription for recording {$recording_sid}: " . $e->getMessage() . "\n";
        }

        return [
            'transcription_sid' => null,
            'transcription_text' => null,
            'status' => null,
            'confidence' => null,
        ];
    }

    private function donwloadRecording(string $media_url, string $recording_sid): array {
        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(config('twilio.twilio.sid').':'.config('twilio.twilio.token')),
        ])->get($media_url);
        
        if ($response->successful()) {
            $file_name = $recording_sid.'.mp3';
            $file_axocall_url ="assets/axocall/recordings/".$file_name;
            
            Storage::disk('recordings')->put($file_name, $response->body());
            $transcription = app(TranscriptionService::class)->transcribeRecording($file_axocall_url, $recording_sid);

            return [
                'recording_url_twilio' => $media_url,
                'recording_url_axocall' => $file_axocall_url,
                'recording_filename' => $file_name,
                'transcription_sid' => $transcription['transcription_sid'] ?? null,
                'transcriptions' => $transcription['transcription_text'] ?? null,
            ];
        }
        
        return ['recording_url_twilio' => '', 'recording_url_axocall' => '', 'recording_filename' => ''];
    }

    private function getCallType(string $from): string {
        return in_array($from, $this->getAccessNumbers()) ? self::OUTBOUND_CALL : self::INBOUND_CALL;
    }

    private function getAccessNumbers(): array {
        return array_map(function($number) {
            return formatHelper()->formatPhoneNumber($number);
        }, explode('|', config('twilio.twilio.number')));
    }
    
}