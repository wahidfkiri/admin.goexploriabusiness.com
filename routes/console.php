<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;



Artisan::command('inspire', function () {
    $this->comment('Stay inspired!');
})->purpose('Display an inspiring quote');

Schedule::command('telescope:prune')->daily();