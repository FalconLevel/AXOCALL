<?php

declare(strict_types=1);
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use OpenAI\Laravel\Facades\OpenAI;
use Twilio\Rest\Client;

class RecordingHelper {
    private $openai;
    private $twilio_client;
    public function __construct() {
        $this->openai = new OpenAI(config('openai.secret'));
        $this->twilio_client = new Client(config('twilio.twilio.sid'), config('twilio.twilio.token'));
    }

    public function transcribeRecording(string $recordingPath, string $recordingSid) {
        
        // to get the transcription of the recording using the recording sid via Conversational Intelligence API
        $recording_sid = 'RE95a1a89070a40fff22730dba767546f0';
        
        $transcripts = $this->twilio_client->intelligence->v2->transcripts->read([
            // 'afterDateCreated' => new \DateTime('2025-06-01T00:00:00Z'),
            // 'beforeDateCreated' => new \DateTime('2025-06-20T23:59:59Z'),
            'sourceSid' => $recording_sid,
        ]);
        // dd($transcripts);
        
        $arr_transcription = $transcripts[0]->toArray();
        // foreach ($transcripts as $transcription) {
            

            // print_r($arr_transcription);
            $account_sid = $arr_transcription['accountSid'];
            $service_sid = $arr_transcription['serviceSid'];
            $sid = $arr_transcription['sid'];
            $recording_sid = $arr_transcription['channel']['media_properties']['source_sid'];

            // echo $recording_sid."\n";
            $sentences = $this->twilio_client->intelligence->v2->transcripts($sid)->sentences->read([]);
            foreach ($sentences as $sentence) {
                print_r($sentence->toArray());
                echo "\n";
            }
            
            // echo "\n";
        // }
        
    }
}