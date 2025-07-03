<?php

use App\Http\Controllers\Exec\ContactController;
use App\Http\Controllers\Exec\ExtensionController;
use App\Http\Controllers\Exec\SettingsController;
use App\Http\Controllers\Exec\TagController;
use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.login');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [ModuleController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/communications', [ModuleController::class, 'communications'])->name('admin.communications');
    Route::get('/contacts', [ModuleController::class, 'contacts'])->name('admin.contacts');
    Route::get('/extensions', [ModuleController::class, 'extensions'])->name('admin.extensions');
    Route::get('/follow_ups', [ModuleController::class, 'follow_ups'])->name('admin.follow_ups');
});

Route::group(['prefix' => 'maintenance'], function () {
    Route::get('/settings', [ModuleController::class, 'settings'])->name('maintenance.settings');
    Route::get('/profile', [ModuleController::class, 'profile'])->name('maintenance.profile');  
});



//Execution Routes

Route::group(['prefix' => 'executor'], function () {
    Route::group(['prefix' => 'tags'], function () {
        Route::post('/all', [TagController::class, 'all'])->name('executor.tags.all');
        Route::post('/save', [TagController::class, 'save'])->name('executor.tags.save');
        Route::post('/delete/{id}', [TagController::class, 'delete'])->name('executor.tags.delete');
    });

    Route::group(['prefix' => 'communications'], function () {
        Route::post('/', [ModuleController::class, 'communications'])->name('executor.communications');
    }); 

    // Contacts routes using Exec\ContactController
    Route::group(['prefix' => 'contacts'], function () {
        Route::post('/all', [ContactController::class, 'all'])->name('executor.contacts.all');
        Route::post('/save', [ContactController::class, 'save'])->name('executor.contacts.save');
        Route::post('/update/{id}', [ContactController::class, 'update'])->name('executor.contacts.update');
        Route::post('/delete/{id}', [ContactController::class, 'delete'])->name('executor.contacts.delete');
        Route::post('/edit/{id}', [ContactController::class, 'edit'])->name('executor.contacts.edit');
    });

    Route::group(['prefix' => 'extensions'], function () {
        Route::post('/all', [ExtensionController::class, 'all'])->name('executor.extensions.all');
        Route::post('/save', [ExtensionController::class, 'save'])->name('executor.extensions.save');
        Route::post('/update/{id}', [ExtensionController::class, 'update'])->name('executor.extensions.update');
        Route::post('/delete/{id}', [ExtensionController::class, 'delete'])->name('executor.extensions.delete');
        Route::post('/edit/{id}', [ExtensionController::class, 'edit'])->name('executor.extensions.edit');

        Route::post('/generate', [ExtensionController::class, 'generate'])->name('executor.extensions.generate');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::post('/extension-settings', [SettingsController::class, 'extensionSettings'])->name('executor.settings.extension-settings');
        Route::post('/save-extension-settings', [SettingsController::class, 'saveExtensionSettings'])->name('executor.settings.save-extension-settings');
    });
});