<?php

namespace App\Domains\Movie\Jobs;

use App\Services\TMDBService;
use App\Services\MovieProcessor;
use App\Domains\Movie\Models\Movie;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateSingleMovie implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Movie ID to update
    protected int $movieId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $movieId)
    {
        $this->movieId = $movieId;
    }

    /**
     * Execute the job.
     */
    public function handle(TMDBService $tmdb, MovieProcessor $processor): void
    {
        Log::info('UpdateSingleMovie: Job started');
        
        try {
            // Fetch the movie from the database
            $movie = Movie::find($this->movieId);

            if (!$movie) {
                Log::warning("UpdateSingleMovie: Movie ID {$this->movieId} not found.");
                return;
            }

            // Fetch movie details from TMDB
            $movieTMDB = $tmdb->getMovieDetails($movie->tmdb_id);
            if (!$movieTMDB) {
                Log::warning("UpdateSingleMovie: TMDB details for Movie ID {$this->movieId} not found.");
                return;
            }
        
            // Update movie details in the database
            $processor->setMoieDetails($movieTMDB, $movieTMDB['genres'] ?? []);
            Log::info("UpdateSingleMovie: Successfully updated Movie ID {$this->movieId} details from TMDB.");
        } catch (\Exception $e) {
            Log::error("UpdateSingleMovie: Failed to update Movie ID {$this->movieId} details from TMDB: {$e->getMessage()}");
            return;
        }
    }
}
