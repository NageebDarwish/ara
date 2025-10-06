<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

use Illuminate\Support\Facades\Schedule;

Schedule::command('expire-subscription')->daily();
Schedule::command('renew-subscription')->daily();
Schedule::command('blogs:publish-scheduled')->everyMinute();
Schedule::command('newsletters:send-scheduled')->everyMinute();
