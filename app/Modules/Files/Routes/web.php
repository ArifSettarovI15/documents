<?php

namespace App\Modules\Files\Routes;


use Illuminate\Support\Facades\Route;
use App\Modules\Files\Controllers\FilesController;


Route::prefix('files')->namespace('App\Modules\Files\Controllers')->group(function(){
    Route::post('add', [FilesController::class, 'add_image'])->name('files.add_image');
    Route::post('add', [FilesController::class, 'add_file'])->name('files.add_file');
});
