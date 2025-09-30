<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Domains\PopularMovie\Jobs\UpdatePopularMovies;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the UpdatePopularMovies job to run hourly
Schedule::job(new UpdatePopularMovies)->hourly();
