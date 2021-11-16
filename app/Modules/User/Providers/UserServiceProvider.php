<?php

namespace App\Modules\User\Providers;

use App\Modules\User\Middlewares\Authenticated;
use App\Modules\User\Middlewares\NotAuthenticated;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('modules', Authenticated::class)->name('Authenticated');
        $router->pushMiddlewareToGroup('modules', NotAuthenticated::class);
    }
}
