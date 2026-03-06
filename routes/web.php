<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebateController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\TrainingSessionController;
use App\Livewire\DebateIndex;
use App\Livewire\PersonIndex;
use App\Livewire\TrainingSessionIndex;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch')
    ->where('locale', 'en|ar');

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('persons', PersonIndex::class)->name('persons.index');
    Route::resource('persons', PersonController::class)->except(['index']);

    Route::get('training-sessions', TrainingSessionIndex::class)->name('training-sessions.index');
    Route::resource('training-sessions', TrainingSessionController::class)->except(['index']);
    Route::get('training-sessions/{trainingSession}/attendance', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('training-sessions/{trainingSession}/attendance', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('debates', DebateIndex::class)->name('debates.index');
    Route::resource('debates', DebateController::class)->except(['index']);

    Route::get('sync', [SyncController::class, 'index'])->name('sync.index');
    Route::post('sync/pull', [SyncController::class, 'pull'])->name('sync.pull');
    Route::post('sync/push', [SyncController::class, 'push'])->name('sync.push');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
