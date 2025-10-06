<?php

namespace App\Domains\PopularMovie\Jobs;

use App\Services\TMDBService;
use Illuminate\Bus\Queueable;
use App\Services\MovieProcessor;
use App\Domains\Genre\Models\Genre;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Exceptions\ApiResponseException;
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

            // If there are new genres save them
            $existingIds = Genre::pluck('tmdb_id')->toArray();

            $allGenreIds = collect($movies)
                ->pluck('genre_ids')
                ->flatten()
                ->unique()
                ->values()
                ->all();
            
            $newGenres = array_filter($allGenreIds, function($genre) use ($existingIds) {
                return !in_array($genre, $existingIds);
            });

            if (!empty($newGenres)) {
                $genres = $tmdb->getGenres();
                foreach ($genres as $genre) {
                    if (in_array($genre['id'], $newGenres)) {
                        Genre::create([
                            'tmdb_id' => $genre['id'],
                            'name' => $genre['name'],
                        ]);
                    }
                }   
            }
            
            // Process and store the movies
            $processor->storePopularMovies($movies);
            Log::info('UpdatePopularMovies: Successfully updated popular movies from TMDB.');
        } catch (ApiResponseException $e) {
            Log::error("UpdatePopularMovies: TMDB API error: {$e->getMessage()}");
        } catch (\Exception $e) {
            Log::error("UpdatePopularMovies: Failed to update popular movies from TMDB: {$e->getMessage()}");
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
