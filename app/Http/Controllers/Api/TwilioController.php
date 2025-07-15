<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Models\Transcription;
use App\Services\SentimentAnalysisService;
use App\Services\TranscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class TwilioController extends Controller
{
    const INBOUND_CALL = 'inbound';
    const OUTBOUND_CALL = 'outbound';
    
    private $twilio_client;
    private $sentimentService;
    
    public function __construct(SentimentAnalysisService $sentimentService) {
        $this->twilio_client = new Client(config('twilio.twilio.sid'), config('twilio.twilio.token'));
        $this->sentimentService = $sentimentService;
    }

    public function getVoice(Request $request) {
        try {
            Log::channel('webhook')->info("REQUEST DATA: " . json_encode($request->all()) . "\n");
            // $transcript_sid = $request->transcript_sid;
            $transcript_sid = 'GT456e955b6a99d3b6c6080f8ca60553bb';
            $transcript = $this->twilio_client->intelligence->v2->transcripts($transcript_sid)->fetch();

            $participants = $this->getParticipants($transcript->channel['participants']);

            $accountSid = $transcript->accountSid;
            $serviceSid = $transcript->serviceSid;
            $status = $transcript->status;
            $recording_sid = $transcript->channel['media_properties']['source_sid'];
            $call_sid = $transcript->channel['media_properties']['reference_sids']['call_sid'];
            $date_time =$transcript->dateCreated;
            $duration = $transcript->duration;
            $from = $participants['from']['from'];
            $from_formatted = $participants['from']['from_formatted'];
            $to = $participants['to']['to'];
            $to_formatted = $participants['to']['to_formatted'];

            $call_type = $this->getCallType($from);
            
            $recording_details = $this->getRecordingDetails(
                $transcript_sid,
                $call_type, [
                    'account_sid' => $accountSid,
                    'service_sid' => $serviceSid,
                    'recording_id' => $recording_sid,
                ]
            );
            
            $communication_data = array_merge($recording_details, [
                'type' => $call_type,
                'from' => $from,
                'from_formatted' => $from_formatted,
                'to' => $to,
                'to_formatted' => $to_formatted,
                'status' => $status,
                'recording_sid' => $recording_sid,
                'call_sid' => $call_sid,
                'date_time' => $date_time,
                'duration' => $duration,
            ]);
            
            $transcript_data = $communication_data['transcription'];
            unset($communication_data['transcription']);
            
            Log::channel('webhook')->info("COMMUNICATION DATA: " . json_encode($communication_data) . "\n");
            Log::channel('webhook')->info("TRANSCRIPT DATA: " . json_encode($transcript_data) . "\n");
            
            DB::beginTransaction();
                if ($communication_data) {

                    Communication::upsert($communication_data, ['call_sid']);

                    if ($transcript_data) {
                        Transcription::upsert($transcript_data, ['transcript_id']);
                    }

                    echo "Recordings fetched successfully\n";
                    Log::channel('webhook')->info("Recordings fetched successfully");
                } else {
                    echo "No recordings found";
                    Log::channel('webhook')->info("No recordings found");
                }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('webhook')->info("Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getRecordingDetails(string $transcript_sid, string $call_type, array $transcript_sub_data): array {
        
        $recording = $this->twilio_client->intelligence->v2->transcripts($transcript_sid)->media()->fetch();

        $media_url = $recording->mediaUrl;
        $aws_url = explode('?', $media_url);
        $recording_url = explode('/', $aws_url[0]);
        $filename = $recording_url[sizeof($recording_url) - 1];
        $file_axocall_url ="assets/axocall/recordings/".$filename;
        $response = Http::timeout(300)->get($media_url);
        
        if ($response->successful()) {
            file_put_contents(public_path($file_axocall_url), $response->body());

            $transcription = $this->getTranscription($transcript_sid, $transcript_sub_data);
            
            if ($transcription) {
            
                $transcription_text = implode(' ', array_column($transcription, 'transcript_sentence'));
                $analysis = $this->sentimentService->analyzeCallRecording($transcription_text, $call_type);

                return [
                    'summary' => $analysis['summary'] ?? null,
                    'sentiment' => $analysis['sentiment'] ?? null,
                    'keywords' => implode(',', $analysis['keywords']),
                    'is_booked' => $analysis['is_booked'] ?? null,
                    'transcription' => $transcription,
                    'recording_url_twilio' => $media_url,
                    'recording_url_axocall' => $file_axocall_url,
                    'recording_filename' => $filename,
                ];
            }
            
            return [
                'summary' => '',
                'sentiment' => '',
                'keywords' => '',
                'is_booked' => '',
                'transcription' => '',
                'recording_url_twilio' => '',
                'recording_url_axocall' => '',
                'recording_filename' => '',
            ];

        }

        return [
            'recording_url_twilio' => '',
            'recording_url_axocall' => '',
            'recording_filename' => '',
            'recording_sid' => '',
        ];
        
    }

    private function getTranscription(string $transcript_sid, array $transcript_sub_data): array {
        try {
            $transcription_data = [];

            $sentences = $this->twilio_client->intelligence->v2->transcripts($transcript_sid)->sentences->read([]);
            foreach ($sentences as $sentence) {
                $sentence_data = $sentence->toArray();
                
                $transcription_data[] = array_merge($transcript_sub_data, [
                    'transcript_id' => $sentence_data['sid'],
                    'transcript_sentence' => $sentence_data['transcript'],
                ]);
            }
            
            return $transcription_data;
        } catch (\Exception $e) {
            logInfo("Error getting transcription for recording {$transcript_sid}: " . $e->getMessage() . "\n");
            return [];
        }
    }

    private function getCallType(string $from): string {
        return in_array(formatHelper()->formatPhoneNumber($from), $this->getAccessNumbers()) ? self::OUTBOUND_CALL : self::INBOUND_CALL;
    }

    private function getAccessNumbers(): array {
        return array_map(function($number) {
            return formatHelper()->formatPhoneNumber($number);
        }, explode('|', config('twilio.twilio.number')));
    }

    private function getParticipants(array $participants): array {

        $participants_data = [];
        foreach ($participants as $participant) {
            if ($participant['role'] == 'Agent') {  
                $participants_data['from'] = [
                    'from' => formatHelper()->formatPhoneNumber($participant['media_participant_id']),
                    'from_formatted' => formatHelper()->formatPhoneNumberWithParenthesis($participant['media_participant_id']),
                ];
            } else {
                $participants_data['to'] = [
                    'to' => formatHelper()->formatPhoneNumber($participant['media_participant_id']),
                    'to_formatted' => formatHelper()->formatPhoneNumberWithParenthesis($participant['media_participant_id']),
                ];
            }
        }
        return $participants_data;
    }

}

// 'type' => $this->getCallType($call->fromFormatted),
// 'from' => $call->from,
// 'from_formatted' => $call->fromFormatted,
// 'to' => $call->to,
// 'to_formatted' => $call->toFormatted,
// 'date_time' => Carbon::parse($call->dateCreated)->format('Y-m-d H:i:s'),
// 'duration' => $call->duration,
// 'call_sid' => $call->sid,
// 'status' => $call->status,
// 'summary' => null,
// 'sentiment' => null,
// 'keywords' => null,
// 'is_booked' => null,

// $callData['summary'] = $analysis['summary'] ?? null;
// $callData['sentiment'] = $analysis['sentiment'] ?? null;
// $callData['keywords'] = implode(',', $analysis['keywords']);
// $callData['is_booked'] = $analysis['is_booked'] ?? null;
                            

// 'recording_url_twilio' => '',
// 'recording_url_axocall' => '',
// 'recording_filename' => '',
// 'recording_sid' => '',