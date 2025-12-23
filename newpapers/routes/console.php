<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('dantri:crawl')->everyTenMinutes()->sendOutputTo(storage_path('logs/dantri_crawl.log'));

Schedule::command('posts:send-publish-reminders')->everyMinute()->withoutOverlapping();

