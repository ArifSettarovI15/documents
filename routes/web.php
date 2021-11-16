<?php


use App\Modules\Files\Controllers\FilesController;
use App\Modules\User\Middlewares\Authenticated;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckAdmin;
use \App\Modules\User\Middlewares\NotAuthenticated;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Проходит проверка на авторизацию пользователя и редирект на route('user.login')
 *
*/
Route::middleware([NotAuthenticated::class])->get('/', function () {
    return view('welcome');
});


Route::middleware([NotAuthenticated::class])->prefix('storage')->group(function(){
    Route::get('/{folder}/{file}', [FilesController::class, 'get_file'])->name('storage');
});
