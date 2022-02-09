<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\TransactionsController;

Route::prefix('api/v1')->group(function () {
    Route::post('/transaction', [TransactionsController::class, 'store'])->name('transactions.store');
    Route::get('/transaction', [TransactionsController::class, 'index'])->name('transactions.index');
    
    Route::get('/transactions/analytics', [TransactionsController::class, 'analytics'])->name('transactions.analytics');    

    // Route::get('/transactions/create', [TransactionsController::class, 'create'])->name('transactions.create');
    // Route::get('/transactions/{transaction}', [TransactionsController::class, 'show'])->name('transactions.show');
    // Route::get('/transactions/{transaction}/edit', [TransactionsController::class, 'edit'])->name('transactions.edit');
    // Route::put('/transactions/{transaction}', [TransactionsController::class, 'update'])->name('transactions.update');
    // Route::delete('/transactions/{transaction}', [TransactionsController::class, 'destroy'])->name('transactions.destroy');
});
