<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

//users
Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('users');
Route::get('/users/add', [UserController::class, 'add'])
    ->middleware(['auth', 'verified'])
    ->name('users/add');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('users.edit');
Route::get('/users/destroy', [UserController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('users.destroy');
Route::patch('/users/add', [UserController::class, 'add']);
Route::patch('/users/{id}/edit', [UserController::class, 'edit']);
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

//members
Route::get('/members', [MemberController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('members');
Route::get('/members/destroy', [MemberController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('members.destroy');
Route::delete('/members/{id}/{type}', [MemberController::class, 'destroy'])->name('members.destroy');

//database backup
Route::get('/database', [DatabaseController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('database');
Route::get('download/{filename}', [BackupController::class, 'download'])->name('download');
Route::get('/backup', [BackupController::class, 'backup'])->name('backup');

//log
Route::get('/logs', action: [LogController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('logs');
Route::get('/logs/view/{id}', [LogController::class, 'view'])
    ->middleware(['auth', 'verified'])
    ->name('logs.view');
Route::post('/logs/mark-all-read', [LogController::class, 'mark_all_read'])->name('logs.mark_all_read');
Route::post('/logs/mark-read/{id}', [LogController::class, 'mark_read'])->name('logs.mark_read');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [AccountController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AccountController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-face-image', [AccountController::class, 'uploadFaceImage'])->name('profile.uploadFaceImage');
    Route::delete('/profile', [AccountController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/reload-captcha', [HomeController::class, 'reloadCaptcha']);

require __DIR__.'/auth.php';
