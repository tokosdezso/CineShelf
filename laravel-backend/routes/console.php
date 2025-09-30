<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Domains\Movie\Jobs\UpdateAllMovies;
use App\Domains\PopularMovie\Jobs\UpdatePopularMovies;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the UpdatePopularMovies job to run hourly
Schedule::job(new UpdatePopularMovies)->hourly();

// Schedule the UpdateAllMovies job to run every day
Schedule::job(new UpdateAllMovies)->daily();