<?php

use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;

use App\Modules\Clients\Controllers\ClientsController;

Route::prefix('manager')->middleware([CheckAdmin::class])->group(function(){
    Route::prefix('clients')->namespace('App\Modules\Clients')->group(function() {
        Route::get('/', [ClientsController::class, 'index'])->name('manager.clients.index');
        Route::post('/', [ClientsController::class, 'index'])->name('manager.clients.index');
        Route::get('add/', [ClientsController::class, 'create'])->name('manager.clients.create');
        Route::post('add/', [ClientsController::class, 'store'])->name('manager.clients.store');
        Route::get('edit/{id}', [ClientsController::class, 'show'])->name('manager.clients.show');
        Route::post('edit/{id}', [ClientsController::class, 'update'])->name('manager.clients.update');
        Route::post('status/', [ClientsController::class, 'status'])->name('manager.clients.status');
        Route::get('info/{id}', [ClientsController::class, 'info'])->name('manager.clients.info');
        Route::post('info/{id}', [ClientsController::class, 'info'])->name('manager.clients.info');
        Route::post('delete/{id}', [ClientsController::class, 'destroy'])->middleware([CheckSuperAdmin::class])->name('manager.clients.destroy');
    });
});
