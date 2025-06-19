<?php

use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.login');
});

Route::get('/admin/dashboard', [ModuleController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/communications', [ModuleController::class, 'communications'])->name('admin.communications');
Route::get('/admin/contacts', [ModuleController::class, 'contacts'])->name('admin.contacts');
Route::get('/admin/extensions', [ModuleController::class, 'extensions'])->name('admin.extensions');
Route::get('/admin/follow_ups', [ModuleController::class, 'follow_ups'])->name('admin.follow_ups');