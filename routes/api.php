<?php

use App\Http\Controllers\Api\CommunicationController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('communications')->group(function () {
    Route::get('/all', [CommunicationController::class, 'all']);
});

Route::prefix('tags')->group(function () {
    Route::post('/all', [TagController::class, 'all']);
    Route::post('/save', [TagController::class, 'save']);
    Route::post('/delete/{id}', [TagController::class, 'delete']);
});