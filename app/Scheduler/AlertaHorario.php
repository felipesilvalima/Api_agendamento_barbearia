<?php declare(strict_types=1); 

namespace App\Scheduler;

use App\Jobs\AlertaHorarioJobs;
use Illuminate\Console\Scheduling\Schedule;

class AlertaHorario
{
    //rodar o comando
    // 1 crontab -e
    //2 php artisan schedule:work

    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new AlertaHorarioJobs)
            ->everyMinute();
    }
}