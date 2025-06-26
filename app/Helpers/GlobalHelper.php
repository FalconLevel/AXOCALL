<?php

declare(strict_types=1);
namespace App\Helpers;

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

}