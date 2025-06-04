<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot(Schedule $schedule): void
    {
        $schedule->command('email:send-manager-daily-sale-report')->dailyAt(env('HOUR_TO_SEND_DAILY_EMAILS', '23:59'));
        $schedule->command('email:send-sellers-daily-commission-reports')->dailyAt(env('HOUR_TO_SEND_DAILY_EMAILS', '23:59'));
    }
}
