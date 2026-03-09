<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// каждые 15 с 9 до 11 часов по Красноярску
Schedule::command('poidu:add-fake-view')
    ->timezone('Asia/Krasnoyarsk')
    ->cron('*/15 9-11 * * *');

// каждые 20 с 13 до 17 часов по Красноярску
Schedule::command('poidu:add-fake-view')
    ->timezone('Asia/Krasnoyarsk')
    ->cron('*/20 13-17 * * *');

// каждый час с 19 до 21 часов по Красноярску
Schedule::command('poidu:add-fake-view')
    ->timezone('Asia/Krasnoyarsk')
    ->cron('0 19-21 * * *');
