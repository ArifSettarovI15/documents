<?php

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckSuperAdmin;
use App\Modules\User\Controllers\ManagerUsersController;
use Illuminate\Support\Facades\Route;

use App\Modules\User\Middlewares\Authenticated;
use App\Modules\User\Middlewares\NotAuthenticated;



Route::middleware([Authenticated::class])->namespace('App\Modules\User\Controllers')->group(function(){

    Route::match(['GET','POST'],'/login', 'UserController@login')->name('user.login');

    Route::match(['GET','POST'],'/forgot','UserController@forgot')->name('user.forgot');
    Route::match(['GET','POST'],'/forgot/change','UserController@forgot_change')->name('forgot.change');
    Route::match(['GET','POST'],'/verify', 'UserController@verify')->name('verification.verify');

//    Route::match(['GET','POST'],'/register', 'UserController@register')->name('user.register');
//    Route::get('/social-auth/{provider}','SocialController@redirectToProvider')->name('user.social');
//    Route::get('/social-auth/{provider}/callback', 'SocialController@handleProviderCallback')->name('user.social.callback');
});


Route::namespace('App\Modules\User\Controllers')->group(function(){
    Route::match(['GET','POST'],'/verify{id}/{hash}', 'UserController@verify')->name('user.verify');
});

Route::middleware([NotAuthenticated::class])->namespace('App\Modules\User\Controllers')->group(function(){
    Route::get('/logout', 'UserController@logout')->name('user.logout');
});

Route::middleware([CheckSuperAdmin::class])->prefix('manager/users')->namespace('App\Modules\User\Controllers')->group(function(){
        Route::get('/', [ManagerUsersController::class, 'index'])->name('manager.users.index');
        Route::get('add/', [ManagerUsersController::class, 'create'])->name('manager.users.create');
        Route::post('add/', [ManagerUsersController::class, 'store'])->name('manager.users.store');
        Route::get('show/{id}',[ManagerUsersController::class, 'show'])->name('manager.users.show');
        Route::post('update/{id}', [ManagerUsersController::class, 'update'])->name('manager.users.update');
        Route::get('delete/{id}', [ManagerUsersController::class,'destroy'])->name('manager.users.destroy');
        Route::post('status/', [ManagerUsersController::class, 'status'])->name('manager.users.status');
});
