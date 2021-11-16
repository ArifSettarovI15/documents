<?php

namespace App\Console\Commands;

use App\Modules\Test\Models\TestModel;
use Illuminate\Console\Command;
use Log;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info("Cron is working fine!");
        $model = new TestModel();
        $model->create([
                           'test_time' => time()
                       ]);
        return "It's working";
    }
}
