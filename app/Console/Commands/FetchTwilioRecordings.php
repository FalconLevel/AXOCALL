<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Communication;
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
    protected $signature = 'app:fetch-twilio-recordings';
    
    protected $description = 'Get recordings from Twilio';

    public function __construct()
    {
        parent::__construct();
        $this->twilio_client = new Client(config('twilio.twilio.sid'), config('twilio.twilio.token'));
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $access_numbers = $this->getAccessNumbers();
        //get calls by date range
        $calls = $this->twilio_client->calls->read([
            "startTimeAfter" => new \DateTime("2025-06-16T00:00:00Z"),
            "startTimeBefore" => new \DateTime("2025-06-20T23:59:59Z"),
        ]);

        $filtered_calls = [];
        foreach ($calls as $call) {
            $recordings_details = $this->getRecordingDetails($call->sid);
            foreach ($recordings_details as $recording_detail) {
                
                $filtered_calls[] = array_merge($recording_detail, [
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
            }
        }

        if ($filtered_calls) {
            Communication::upsert($filtered_calls, ['call_sid']);
            echo "Recordings fetched successfully";
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
                $filtered_recordings[] = array_merge($media_details, ['recording_sid' => $recording->sid]);
            }
            return $filtered_recordings;
        }

        return [[
            'recording_url_twilio' => '',
            'recording_url_axocall' => '',
            'recording_filename' => '',
            'recording_sid' => ''
        ]];
    }

    private function donwloadRecording(string $media_url, string $recording_sid): array {
        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(config('twilio.twilio.sid').':'.config('twilio.twilio.token')),
        ])->get($media_url);
        
        if ($response->successful()) {
            $file_name = $recording_sid.'.mp3';
            $file_axocall_url ="assets/axocall/recordings/".$file_name;
            
            Storage::disk('recordings')->put($file_name, $response->body());
            
            return [
                'recording_url_twilio' => $media_url,
                'recording_url_axocall' => $file_axocall_url,
                'recording_filename' => $file_name,
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
    
    // private function getTranscriptionDetails($recording_sid, $media_url)
    // {
    //     $transcription_url = $media_url.'.json';
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Basic '.base64_encode(config('twilio.twilio.sid').':'.config('twilio.twilio.token')),
    //     ])->get($media_url);
        
    //     if ($response->successful()) {
    //         $file_name = $recording_sid.'.mp3';
    //         $file_path = storage_path('app/recordings/'.$file_name);
    //         $file_url = Storage::disk('recordings')->put($file_name, $response->body());
            
    //         return [
    //             'recording_url' => $media_url,
    //             'recording_filename' => $file_name,
    //         ];
    //     }
        
    //     return ['recording_url' => '', 'recording_filename' => ''];
    // }

}