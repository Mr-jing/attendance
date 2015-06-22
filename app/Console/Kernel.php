<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\SpiderRecordCommand',
        'App\Console\Commands\SpiderUserCommand',
        'App\Console\Commands\RankCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        date_default_timezone_set('Asia/Shanghai');

        $schedule->command('spider:record')
            ->dailyAt('03:00')
            ->sendOutputTo(storage_path() . '/logs/spider_record_' . date('Y-m-d-H-i-s') . '.txt');

        $schedule->command('rank')
            ->dailyAt('03:30')
            ->sendOutputTo(storage_path() . '/logs/redis_rank_' . date('Y-m-d-H-i-s') . '.txt');
    }
}
