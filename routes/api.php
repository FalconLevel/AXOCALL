<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CommunicationController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ExtensionController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('account')->group(function () {
    Route::post('/register', [AccountController::class, 'register']);
    Route::post('/login', [AccountController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('account')->group(function () {
        Route::post('/logout', [AccountController::class, 'logout']);
    });

    Route::prefix('communications')->group(function () {
        Route::get('/all', [CommunicationController::class, 'all']);
        Route::get('/with-sentiment', [CommunicationController::class, 'withSentiment']);
        Route::get('/stats', [CommunicationController::class, 'sentimentStats']);
        Route::post('/analyze/{id}', [CommunicationController::class, 'analyze']);
    });
    
    Route::prefix('tags')->group(function () {
        Route::post('/all', [TagController::class, 'all']);
        Route::post('/save', [TagController::class, 'save']);
        Route::post('/delete/{id}', [TagController::class, 'delete']);
    });
    
    Route::prefix('settings')->group(function () {
        Route::post('/extension-settings', [SettingsController::class, 'extensionSettings']);
        Route::post('/save-extension-settings', [SettingsController::class, 'saveExtensionSettings']);
    });
    
    Route::prefix('contacts')->group(function () {
        Route::post('/all', [ContactController::class, 'all']);
        Route::post('/save', [ContactController::class, 'save']);
        Route::post('/edit/{id}', [ContactController::class, 'edit']);
        Route::post('/update/{id}', [ContactController::class, 'update']);
        Route::post('/delete/{id}', [ContactController::class, 'delete']);
        Route::post('/{id}/phone-numbers', [ContactController::class, 'phoneNumbers']);
        Route::post('/view/{id}', [ContactController::class, 'view']);
    });
    
    Route::prefix('extensions')->group(function () {
        Route::post('/all', [ExtensionController::class, 'all']);
        Route::post('/save', [ExtensionController::class, 'save']);
        Route::post('/edit/{id}', [ExtensionController::class, 'edit']);
        Route::post('/update/{id}', [ExtensionController::class, 'update']);
        Route::post('/delete/{id}', [ExtensionController::class, 'delete']);
    });
    
    Route::prefix('communications')->group(function () {
        Route::post('/transcribe-recording', [CommunicationController::class, 'transcribeRecording']);
        Route::post('/archive/{id}/{type}', [CommunicationController::class, 'archive']);
        Route::post('/un-archive/{id}/{type}', [CommunicationController::class, 'unArchive']);
        Route::post('/update-notes/{id}', [CommunicationController::class, 'updateNotes']);
        Route::post('/follow-up/{id}/{type}', [CommunicationController::class, 'followUp']);
        Route::post('/un-follow-up/{id}/{type}', [CommunicationController::class, 'unFollowUp']);
        Route::post('/refresh-datatable', [CommunicationController::class, 'refreshDatatable']);
    });
});