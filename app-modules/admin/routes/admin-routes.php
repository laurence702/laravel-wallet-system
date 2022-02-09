<?php

use Modules\Admin\Http\Controllers\AdminController;

Route::prefix('api/v1')->group(function () {
    // Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
// Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
// Route::get('/admins/{admin}', [AdminController::class, 'show'])->name('admins.show');
// Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
// Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
// Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');

   Route::delete('/banUser/{user_id}', [AdminController::class, 'banUser'])->name('admins.banUser');
   Route::put('/liftBan/{user_id}', [AdminController::class, 'liftBan'])->name('admins.liftBan');
   Route::put('/admin/fundwallet', [AdminController::class, 'fundUserWallet'])->name('admins.fundWallet');
   Route::put('/admin/verifyUser', [AdminController::class, 'verifyUser'])->name('admins.verifyUser');
});