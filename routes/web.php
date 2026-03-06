<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebateController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\TrainingSessionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('persons', PersonController::class);

    Route::resource('training-sessions', TrainingSessionController::class);
    Route::get('training-sessions/{trainingSession}/attendance', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('training-sessions/{trainingSession}/attendance', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::resource('debates', DebateController::class);

    Route::get('sync', [SyncController::class, 'index'])->name('sync.index');
    Route::post('sync/pull', [SyncController::class, 'pull'])->name('sync.pull');
    Route::post('sync/push', [SyncController::class, 'push'])->name('sync.push');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
