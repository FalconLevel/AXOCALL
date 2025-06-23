<?php

declare(strict_types=1);
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiHelper {
    const REQUEST_TYPE = 'POST';
    
    public function execute(Request $request, string $url): array {
        $api_call = $request->create($url, self::REQUEST_TYPE);
        $response = Route::dispatch($api_call);
        
        return json_decode($response->getContent(), true);
    }
}