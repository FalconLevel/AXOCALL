<?php

use App\Http\Controllers\Api\CommunicationController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


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

Route::prefix('contacts')->group(function () {
    Route::post('/all', [ContactController::class, 'all']);
    Route::post('/save', [ContactController::class, 'save']);
    Route::post('/edit/{id}', [ContactController::class, 'edit']);
    Route::post('/update/{id}', [ContactController::class, 'update']);
    Route::post('/delete/{id}', [ContactController::class, 'delete']);
    Route::post('/{id}/phone-numbers', [ContactController::class, 'phoneNumbers']);
});


use App\Http\Controllers\Api\ExtensionController;

Route::prefix('extensions')->group(function () {
    Route::post('/all', [ExtensionController::class, 'all']);
    Route::post('/save', [ExtensionController::class, 'save']);
    Route::post('/edit/{id}', [ExtensionController::class, 'edit']);
    Route::post('/update/{id}', [ExtensionController::class, 'update']);
    Route::post('/delete/{id}', [ExtensionController::class, 'delete']);
});

Route::prefix('communications')->group(function () {
    Route::post('/archive/{id}/{type}', [CommunicationController::class, 'archive']);
    Route::post('/un-archive/{id}/{type}', [CommunicationController::class, 'unArchive']);
    Route::post('/transcribe-recording', [CommunicationController::class, 'transcribeRecording']);
    Route::post('/update-notes/{id}', [CommunicationController::class, 'updateNotes']);
    Route::post('/follow-up/{id}/{type}', [CommunicationController::class, 'followUp']);
    Route::post('/refresh-datatable', [CommunicationController::class, 'refreshDatatable']);
});