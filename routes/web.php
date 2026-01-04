<?php

use App\Http\Controllers\PinterestAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\PostReview;
use App\Livewire\BoardManager;
use App\Livewire\BoardDetail;
use App\Livewire\BulkScheduler;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware(['auth'])->group(function () {
    // Pinterest Connect Routes
    Route::get('/pinterest/connect', [PinterestAuthController::class, 'redirect'])->name('pinterest.connect');
    Route::get('/pinterest/callback', [PinterestAuthController::class, 'callback'])->name('pinterest.callback');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/post/{id}/review', PostReview::class)->name('post.review');
    Route::get('/boards', BoardManager::class)->name('boards');
    Route::get('/boards/{id}', BoardDetail::class)->name('board.detail');
});


Route::get('/scheduler', BulkScheduler::class)->name('scheduler');
require __DIR__.'/auth.php';
