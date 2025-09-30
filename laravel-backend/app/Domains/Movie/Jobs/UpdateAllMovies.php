<?php

namespace App\Domains\Movie\Jobs;

use App\Domains\Movie\Models\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateAllMovies implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Process movies in chunks to avoid memory issues
        Movie::chunk(1000, function ($movies) {
            foreach ($movies as $movie) {
                // Dispatch a job to update each movie's details
                UpdateSingleMovie::dispatch($movie->id);
            }
        });
    }
}
