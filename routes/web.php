<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Receipts;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/receipts', Receipts\Index::class)->name('receipts.index');
    Route::get('/receipts/create', Receipts\Create::class)->name('receipts.create');
    Route::get('/receipts/{receipt}', Receipts\Show::class)->name('receipts.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
