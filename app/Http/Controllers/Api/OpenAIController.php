<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIController extends Controller
{
    public function sentimentAnalysis(Request $request) {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a sentiment analysis expert. Analyze the following text and determine the sentiment of the text. The sentiment can be positive, negative, or neutral. Please return the sentiment in a JSON format with the key "sentiment" and the value can be "positive", "negative", or "neutral".'],
                    ['role' => 'user', 'content' => $request->text],
                ],
            ]);
            
            return response()->json(['status' => true, 'data' => json_decode($response->choices[0]->message->content)]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}