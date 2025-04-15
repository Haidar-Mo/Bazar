<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:expires-ads')->everyMinute();
Schedule::command('app:expires-subscription')->everyMinute();
Schedule::command('app:delete-old-notifications')->dailyAt('00:00');
