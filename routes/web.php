<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\EnsureEventTicketIsValid;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/events', [EventController::class, 'index'])->name('events.index');

Route::post('/events/{event}/join', [EventController::class, 'joinQueue'])->name('events.join');

Route::get('/events/{event}/waiting-room', [EventController::class, 'waitingRoom'])->name('events.waiting-room');

Route::middleware(EnsureEventTicketIsValid::class)->group(function () {

    Route::get('/events/{event}/checkout', [EventController::class, 'checkout'])->name('events.checkout');

    Route::post('/events/{event}/purchase', [EventController::class, 'purchase'])->name('events.purchase');
});

Route::get('/events/{event}/status', [EventController::class, 'checkQueueStatus'])->name('events.status');

require __DIR__.'/auth.php';
