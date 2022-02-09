<?php

use Modules\User\Http\Controllers\UsersController;


Route::prefix('api/v1')->group(function () {
    // Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::post('/users/login', [UsersController::class, 'login'])->name('users.login');
    // Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');    
});
