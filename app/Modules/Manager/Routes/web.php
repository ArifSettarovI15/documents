<?php


use App\Http\Middleware\CheckAdmin;
use App\Modules\Manager\Controllers\ManagerController;

Route::middleware([CheckAdmin::class])->prefix('manager')->group(function(){
    Route::get('/', [ManagerController::class, 'index'])->name('manager.index');
    Route::post('/', [ManagerController::class, 'index'])->name('manager.index');
    Route::get('log_actions/', [ManagerController::class, 'log_actions'])->name('manager.log_actions');
    Route::post('log_actions/', [ManagerController::class, 'log_actions'])->name('manager.log_actions');
    Route::post('get_smtp_status/', [ManagerController::class, 'get_smtp_status'])->name('manager.get_smtp_status');
    Route::post('get_cloudconvert_status/', [ManagerController::class, 'get_cloudconvert_status'])->name('manager.get_cloudconvert_status');
});
