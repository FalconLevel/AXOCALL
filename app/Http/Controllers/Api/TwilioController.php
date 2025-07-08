<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;

class TwilioController extends Controller
{
    public function getVoice(Request $request) {
        globalHelper()->logInfo(json_encode($request->all()));
    }
}