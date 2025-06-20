<?php

use App\Http\Controllers\Api\CommunicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('communications')->group(function () {
    Route::get('/all', [CommunicationController::class, 'all']);
});