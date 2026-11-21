<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Automated Daily Backup & Maintenance Schedules
|--------------------------------------------------------------------------
*/

// 1. Run full database and media files backup daily at 02:00 AM
Schedule::command('app:backup --type=full')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();

// 2. Clean up old backups based on retention policy daily at 02:30 AM
Schedule::command('backup:clean')
    ->dailyAt('02:30')
    ->withoutOverlapping()
    ->runInBackground();

// 3. Monitor backup health daily at 03:00 AM
Schedule::command('backup:monitor')
    ->dailyAt('03:00')
    ->runInBackground();
