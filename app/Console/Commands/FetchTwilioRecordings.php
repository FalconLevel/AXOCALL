<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Communication;
use App\Models\Transcription;
use App\Services\SentimentAnalysisService;
use App\Services\TranscriptionService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
    protected $signature = 'app:fetch-twilio-recordings {--start-date=} {--end-date=}';
    
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
        // dd($this->option('start-date'), $this->option('end-date'));
        DB::beginTransaction();
        try {
            $access_numbers = $this->getAccessNumbers();
            $start_date = $this->option('start-date') ? date('Y-m-d', strtotime($this->option('start-date'))) : date('Y-m-d', strtotime('-1 day'));
            $end_date = $this->option('end-date') ? date('Y-m-d', strtotime($this->option('end-date'))) : date('Y-m-d');
            
            //get calls by date range
            $calls = $this->twilio_client->calls->read([
                "startTimeAfter" => new \DateTime($start_date."T00:00:00Z"),
                "startTimeBefore" => new \DateTime($end_date."T23:59:59Z"),
            ], 20);
            

            $filtered_calls = [];
            $filtered_transcription = [];
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
                        'summary' => null,
                        'sentiment' => null,
                        'keywords' => null,
                        'is_booked' => null,
                    ]);

                    if ($recording_detail['recording_sid']) {

                        $transcription = $this->getTranscription($recording_detail['recording_sid']);
                        
                        if ($transcription) {
                            
                            $transcription_text = implode(' ', array_column($transcription, 'transcript_sentence'));
                            $analysis = $this->sentimentService->analyzeCallRecording($transcription_text, $callData['type']);
                            
                            $callData['summary'] = $analysis['summary'] ?? null;
                            $callData['sentiment'] = $analysis['sentiment'] ?? null;
                            $callData['keywords'] = $analysis['sentiment_keyword_hits'][$analysis['sentiment']] ?? null;
                            $callData['is_booked'] = $analysis['is_booked'] ?? null;
                            
                            $filtered_transcription = array_merge($filtered_transcription, $transcription);
                        }
                    }

                    $filtered_calls[] = $callData;
                }
            }
        
            
            if ($filtered_calls) {
                Communication::upsert($filtered_calls, ['call_sid']);

                if ($filtered_transcription) {
                    Transcription::upsert($filtered_transcription, ['transcript_id']);
                }
                echo "Recordings fetched successfully\n";
            } else {
                echo "No recordings found";
            }
        } catch (\Exception $e) {
            DB::rollBack();
            logInfo("Error: " . $e->getTraceAsString());
        }
        
        DB::commit();
        
    }

    private function getRecordingDetails(string $call_sid): array {
        $filtered_recordings = [];
        $recordings = $this->twilio_client->recordings->read(['callSid' => $call_sid]);
        
        if ($recordings) {
            foreach ($recordings as $recording) {
                $media_details = $this->donwloadRecording($recording->mediaUrl, $recording->sid);
                
                $filtered_recordings[] = array_merge($media_details, [
                    'recording_sid' => $recording->sid,
                ]);
            }
            return $filtered_recordings;
        }

        return [[
            'recording_url_twilio' => '',
            'recording_url_axocall' => '',
            'recording_filename' => '',
            'recording_sid' => '',
        ]];
    }

    private function getTranscription(string $recording_sid): array {
        try {
            $transcription_data = [];
            $transcripts = $this->twilio_client->intelligence->v2->transcripts->read([
                'sourceSid' => $recording_sid,
            ]);

            if ($transcripts) {
                $arr_transcription = $transcripts[0]->toArray();

                $account_sid = $arr_transcription['accountSid'];
                $service_sid = $arr_transcription['serviceSid'];
                $sid = $arr_transcription['sid'];
                $recording_sid = $arr_transcription['channel']['media_properties']['source_sid'];

                $sentences = $this->twilio_client->intelligence->v2->transcripts($sid)->sentences->read([]);
                foreach ($sentences as $sentence) {
                    $sentence_data = $sentence->toArray();
                    
                    $transcription_data[] = [
                        'account_sid' => $account_sid,
                        'service_sid' => $service_sid,
                        'transcript_id' => $sid,
                        'recording_id' => $recording_sid,
                        'transcript_id' => $sentence_data['sid'],
                        'transcript_sentence' => $sentence_data['transcript'],
                    ];
                }
            }
            
            return $transcription_data;
        } catch (\Exception $e) {
            logInfo("Error getting transcription for recording {$recording_sid}: " . $e->getMessage() . "\n");
            return [];
        }
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
            ];
        }
        
        return ['recording_url_twilio' => '', 'recording_url_axocall' => '', 'recording_filename' => ''];
    }

    private function getCallType(string $from): string {
        
        print_r([
            formatHelper()->formatPhoneNumber($from), $this->getAccessNumbers()
        ]);
        return in_array(formatHelper()->formatPhoneNumber($from), $this->getAccessNumbers()) ? self::OUTBOUND_CALL : self::INBOUND_CALL;
    }

    private function getAccessNumbers(): array {
        return array_map(function($number) {
            return formatHelper()->formatPhoneNumber($number);
        }, explode('|', config('twilio.twilio.number')));
    }
    
}