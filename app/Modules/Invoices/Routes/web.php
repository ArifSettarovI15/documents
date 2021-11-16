<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;

use App\Modules\Invoices\Controllers\InvoicesController;

Route::prefix('manager')->middleware([CheckAdmin::class])->group(function(){
    Route::prefix('invoices')->namespace('App\Modules\Invoices')->group(function() {
        Route::get('/', [InvoicesController::class, 'index'])->name('manager.invoices.index');
        Route::get('add/', [InvoicesController::class, 'create'])->name('manager.invoices.create');
        Route::post('add/', [InvoicesController::class, 'store'])->name('manager.invoices.store');
        Route::get('edit/{id}', [InvoicesController::class, 'show'])->name('manager.invoices.show');
        Route::post('edit/{id}', [InvoicesController::class, 'update'])->name('manager.invoices.update');
        Route::post('status/', [InvoicesController::class, 'status'])->name('manager.invoices.status');
        Route::get('client/{id}', [InvoicesController::class, 'client_invoices'])->name('manager.invoices.client');
        Route::post('client/{id}', [InvoicesController::class, 'client_invoices'])->name('manager.invoices.client');
        Route::get('create_invoices/', [InvoicesController::class, 'create_invoices'])->name('manager.invoices.create_invoices');
        Route::get('file/{id}', [InvoicesController::class, 'file'])->name('manager.invoices.file');
        Route::post('get_by_contract/', [InvoicesController::class, 'create_invoice_by_contract'])->name('manager.invoices.get_by_contract');
        Route::post('create_custom_invoice/', [InvoicesController::class, 'create_custom_invoice'])->name('manager.invoices.create_custom_invoice');

        Route::get('history/', [InvoicesController::class, 'history'])->name('manager.invoices.history');
        Route::post('history/', [InvoicesController::class, 'history'])->name('manager.invoices.history');
        Route::post('history/redo_send/{id}', [InvoicesController::class, 'redo_send'])->name('manager.invoices.redo_send');
        Route::post('history/check_payed/{id}', [InvoicesController::class, 'check_payed'])->name('manager.invoices.check_payed');
        Route::post('history/off_send/{id}', [InvoicesController::class, 'off_send'])->name('manager.invoices.off_send');
        Route::get('not_payed/', [InvoicesController::class, 'not_payed'])->name('manager.invoices.not_payed');
    });
});
