<?php

use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;

use App\Modules\Services\Controllers\ServicesController;

Route::prefix('manager')->middleware([CheckAdmin::class])->group(function(){
    Route::prefix('services')->namespace('App\Modules\Services')->group(function() {
        Route::get('/', [ServicesController::class, 'index'])->name('manager.services.index');
        Route::get('add/', [ServicesController::class, 'create'])->name('manager.services.create');
        Route::post('add/', [ServicesController::class, 'store'])->name('manager.services.store');
        Route::get('edit/{id}/', [ServicesController::class, 'show'])->name('manager.services.show');
        Route::post('edit/{id}/', [ServicesController::class, 'update'])->name('manager.services.update');
        Route::post('status/', [ServicesController::class, 'status'])->name('manager.services.status');
        Route::post('delete/{id}', [ServicesController::class, 'destroy'])->middleware([CheckSuperAdmin::class])->name('manager.services.destroy');
    });
});
