<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;

Route::get('/', function () {
    return redirect()->route('books.index');
});

// Books routes
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/create', [BookController::class, 'create'])->name('create');
    Route::post('/', [BookController::class, 'store'])->name('store');
    Route::get('/{book}/edit', [BookController::class, 'edit'])->name('edit');
    Route::put('/{book}', [BookController::class, 'update'])->name('update');
    Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
    Route::get('/{book}/borrow', [BookController::class, 'borrowForm'])->name('borrowForm');
    Route::post('/{book}/borrow', [BookController::class, 'borrow'])->name('borrow');
});

// Members routes
Route::prefix('members')->name('members.')->group(function () {
    Route::get('/', [MemberController::class, 'index'])->name('index');
    Route::get('/create', [MemberController::class, 'create'])->name('create');
    Route::post('/', [MemberController::class, 'store'])->name('store');
    Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('edit');
    Route::put('/{member}', [MemberController::class, 'update'])->name('update');
    Route::delete('/{member}', [MemberController::class, 'destroy'])->name('destroy');
});

// Loans routes
Route::prefix('loans')->name('loans.')->group(function () {
    Route::get('/', [LoanController::class, 'index'])->name('index');
    Route::post('/{loan}/return', [LoanController::class, 'return'])->name('return');
});
