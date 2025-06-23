<?php

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
    
    
});