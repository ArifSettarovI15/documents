<?php

/**Facades*/

use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Support\Facades\Route;

/**Middlewares*/
use App\Http\Middleware\CheckAdmin;

/**Controllers*/
use App\Modules\Contracts\Controllers\ContractTypesController;
use App\Modules\Contracts\Controllers\ContractsController;
use App\Modules\Contracts\Controllers\ContractsServicesController;



Route::prefix('manager')->middleware([CheckAdmin::class])->group(function(){

    Route::prefix('contracts')->namespace('App\Modules\Contracts')->group(function()
    {
        Route::get('/', [ContractsController::class, 'index'])->name('manager.contracts.index');
        Route::post('/', [ContractsController::class, 'index'])->name('manager.contracts.index');
        Route::get('add/', [ContractsController::class, 'create'])->name('manager.contracts.create');
        Route::post('add/', [ContractsController::class, 'store'])->name('manager.contracts.store');
        Route::get('edit/{id}', [ContractsController::class, 'show'])->name('manager.contracts.show');
        Route::post('edit/{id}', [ContractsController::class, 'update'])->name('manager.contracts.update');
        Route::post('status/', [ContractsController::class, 'status'])->name('manager.contracts.status');
        Route::post('delete/{id}', [ContractsController::class, 'destroy'])->middleware([CheckSuperAdmin::class])->name('manager.contracts.destroy');
        Route::get('document/{id}', [ContractsController::class, 'document'])->name('manager.contracts.document');
        Route::post('convert/{id}', [ContractsController::class, 'convert_to_doc'])->name('manager.contracts.convert_to_doc');
        Route::post('send_to_client/{id}/', [ContractsController::class, 'send_to_client'])->name('manager.contracts.send_to_client');

        Route::prefix('type')->group(function()
        {
            Route::get('/', [ContractTypesController::class, 'index'])->name('manager.contract_types.index');
            Route::get('add/', [ContractTypesController::class, 'create'])->name('manager.contract_types.create');
            Route::post('add/', [ContractTypesController::class, 'store'])->name('manager.contract_types.store');
            Route::get('edit/{id}', [ContractTypesController::class, 'show'])->name('manager.contract_types.show');
            Route::post('edit/{id}', [ContractTypesController::class, 'update'])->name('manager.contract_types.update');
            Route::post('delete/{id}', [ContractTypesController::class, 'destroy'])->middleware([CheckSuperAdmin::class])->name('manager.contract_types.destroy');
            Route::post('status/', [ContractTypesController::class, 'status'])->name('manager.contract_types.status');
            Route::post('services/', [ContractTypesController::class, 'get_services'])->name('manager.contract_types.services');

        });
    });

    Route::prefix('cs')->namespace('App\Modules\Contracts')->group(function()
    {
        Route::get('/', [ContractsServicesController::class, 'index'])->name('manager.contracts_services.index');
        Route::post('add/', [ContractsServicesController::class, 'store'])->name('manager.contracts_services.store');
        Route::post('update/{id}', [ContractsServicesController::class, 'update'])->name('manager.contracts_services.update');
        Route::post('delete/{id}', [ContractsServicesController::class, 'destroy'])->middleware([CheckSuperAdmin::class])->name('manager.contracts_services.destroy');
    });
});
