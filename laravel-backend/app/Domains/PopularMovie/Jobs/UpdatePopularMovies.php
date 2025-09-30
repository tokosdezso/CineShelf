<?php

namespace App\Domains\PopularMovie\Jobs;

use App\Services\TMDBService;
use Illuminate\Bus\Queueable;
use App\Services\MovieProcessor;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePopularMovies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TMDBService $tmdb, MovieProcessor $processor)
    {
        Log::info('UpdatePopularMovies: job started');

        try {
          // Fetch popular movies from TMDB
          $movies = $tmdb->getPopularMovies();

          if (empty($movies)) {
              Log::info('UpdatePopularMovies: No movies fetched from TMDB.');
              return;
          }

          // Process and store the movies
          $processor->storePopularMovies($movies);
          Log::info('UpdatePopularMovies: Successfully updated popular movies from TMDB.');
        } catch (\Exception $e) {
            Log::error("UpdatePopularMovies: Failed to update popular movies from TMDB: {$e->getMessage()}");
            return;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("UpdatePopularMovies: Job failed with exception: {$exception->getMessage()}");
    }
}
