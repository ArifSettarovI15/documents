<?php

namespace App\Modules;


use Illuminate\Support\ServiceProvider;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;




class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $modules = config("modules.modules");
        if ($modules) {
            foreach ($modules as $module){
                if (file_exists(__DIR__ . '/' . $module . '/Routes/web.php')) {
                    $this->loadRoutesFrom(__DIR__ . '/' . $module . '/Routes/web.php');
                }
                if (file_exists(__DIR__ . '/' . $module . '/Views')) {
                    $this->loadViewsFrom(__DIR__ . '/' . $module . '/Views', $module);
                    $this->publishes([
                                         __DIR__ . '/' . $module . '/Views' => resource_path('views/vendor/'.$module),
                                     ]);
                }
                if (file_exists(__DIR__ . '/' . $module . '/Migrations')) {
                    $this->loadMigrationsFrom(__DIR__ . '/' . $module . '/Migrations');
                }
            }
        }
    }
}
