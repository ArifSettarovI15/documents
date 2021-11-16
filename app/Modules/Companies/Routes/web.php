<?php

/**Facades*/

use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Support\Facades\Route;

/**Middlewares*/
use App\Http\Middleware\CheckAdmin;

/**Controllers*/

use App\Modules\Companies\Controllers\CompaniesController;




Route::prefix('manager')->middleware([CheckAdmin::class])->group(function(){

    Route::prefix('companies')->namespace('App\Modules\Companies')->group(function() {
        Route::get('/', [CompaniesController::class, 'index'])->name('manager.companies.index');
        Route::get('add/', [CompaniesController::class, 'create'])->name('manager.companies.create');
        Route::post('add/', [CompaniesController::class, 'store'])->name('manager.companies.store');
        Route::get('edit/{id}', [CompaniesController::class, 'show'])->name('manager.companies.show');
        Route::post('edit/{id}', [CompaniesController::class, 'update'])->name('manager.companies.update');
        Route::post('status/', [CompaniesController::class, 'status'])->name('manager.companies.status');
//        Route::post('delete/{id}', [CompaniesController::class, 'destroy'])->middleware([CheckSuperAdmin::class])->name('manager.companies.destroy');
    });
});
