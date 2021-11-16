<?php

use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;

use App\Modules\Plans\Controller\PlansController;

Route::prefix('manager')->middleware([CheckAdmin::class])->group(function(){
    Route::prefix('plans')->namespace('App\Modules\Plans')->group(function() {
        Route::get('/', [PlansController::class, 'index'])->name('manager.plans.index');
        Route::get('add/', [PlansController::class, 'create'])->name('manager.plans.create');
        Route::post('add/', [PlansController::class, 'store'])->name('manager.plans.store');
        Route::get('edit/{id}', [PlansController::class, 'show'])->name('manager.plans.show');
        Route::post('edit/{id}', [PlansController::class, 'update'])->name('manager.plans.update');
        Route::post('status/', [PlansController::class, 'status'])->name('manager.plans.status');
        Route::post('delete/{id}', [PlansController::class, 'destroy'])->middleware([CheckSuperAdmin::class])->name('manager.plans.destroy');
    });
});
