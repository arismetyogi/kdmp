<?php

use App\Livewire\Claim\BulkUpload;
use App\Livewire\Claim\DocumentUpload;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Users;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('upload-dokumen-klaim', DocumentUpload\Index::class)->name('claim-document-upload.index');
    Route::get('upload-dokumen-klaim/{id}', DocumentUpload\Upload::class)->name('claim-document-upload.upload');

    Route::middleware('is_admin:99')->group(function () {
        Route::get('upload-penjamin', BulkUpload::class)->name('claim-upload');
        Route::get('users', Users\Index::class)->name('users.index');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
