<?php

namespace App\Console;


use App\Modules\Invoices\Controllers\InvoicesController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
//    protected $commands = [
//
//    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule):void
    {
        $schedule->call(function (){

            $invoices = new InvoicesController();
            $invoices->update_plan();
            $invoices->create_invoices();
            $invoices->send_invoices_queue();
            $invoices->check_notPayed();
            $invoices->send_notPayed_after5days();

        })->timezone('Europe/Moscow')->dailyAt('9:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands():void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
