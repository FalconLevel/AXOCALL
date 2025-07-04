<?php

declare(strict_types=1);
namespace App\Helpers;

use App\Models\Communication;
use App\Models\Contact;
use App\Models\Extension;
use App\Models\Message;
use App\Models\PhoneNumber;
use App\Models\SettingExtension;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GlobalHelper {
    
    public function ajaxSuccessResponse(
        string $type="toast", 
        string $toast_type='success',
        string $exec='', 
        string $message='',
        string $title='System Info',
        array $data=[]
    ): JsonResponse {
        
        if ($type == 'toast') {
            $response = responseHelper()->toastrResponse($message, $toast_type, $title);
        } else if ($type == 'scripts') {
            $response = responseHelper()->scriptResponse($exec, $data, $message, $toast_type, $title);
        }
        
        return response()->json($response, 200);
    }

    public function ajaxErrorResponse(string $message='', string $url='', string $title='System Error'): JsonResponse {
        $response = responseHelper()->toastrResponse($message, 'error', $title);
        return response()->json($response, 200);
    }

    public function logInfo(string $message): void {
        Log::channel('info')->info($message);
    }

    public function getTags(): array {
        try {
            $tags = Tag::orderBy('tag_name', 'asc')->get();
            return $tags->toArray();
        } catch (\Exception $e) {
            $this->logInfo("Error fetching tags: " . $e->getMessage());
            return [];
        }
    }

    public function getCommunicationData() {
        $communications = Communication::orderBy('date_time', 'desc')->with('transcriptions', 'contact_from', 'contact_to')->get();
        
        return $communications;
    }

    public function getMessageData() {
        $messages = Message::orderBy('date_sent', 'desc')->with('contact_from', 'contact_to')->get();
        
        return $messages;
    }

    public function generateExtension() {
        try {
            $setting_extension = SettingExtension::first();
            if (!$setting_extension) {
                return [
                    'extension_number' => '',
                    'expiration_date' => '',
                ];
            }
            $extension_expiration_days = $setting_extension->extension_expiration_days;
            $extension_expiration_hrs = $setting_extension->extension_expiration_hrs;
            $random_extension_generation = $setting_extension->random_extension_generation;

            if ($random_extension_generation) {
                $extension_new = $this->generateRandomExtension();
            } else {
                $extension_new = $this->generateSequentialExtension();
            }
            
            $expiration_date = now()->addDays($extension_expiration_days)->addHours($extension_expiration_hrs)->format('Y-m-d H:i A');
            return [
                'extension_number' => $extension_new,
                'expiration_date' => $expiration_date,
            ];
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return [
                'extension_number' => '',
                'expiration_date' => '',
            ];
        }
    }

    private function generateRandomExtension() {
        $extension_new = rand(1000, 9999);
        return $extension_new;
    }

    private function generateSequentialExtension() {
        $last_extension = Extension::orderBy('id', 'desc')->first();
        return $last_extension ? $last_extension->extension_number + 1 : config('custom.extension_start');
    }
}