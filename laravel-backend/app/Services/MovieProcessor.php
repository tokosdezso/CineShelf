<?php

namespace App\Services;

use App\Domains\Genre\Models\Genre;
use App\Domains\Movie\Models\Movie;
use App\Domains\PopularMovie\Models\PopularMovie;


class MovieProcessor
{
    /**
     * Store or update popular movies in the database.
     * @param array $movies
     */
    public function storePopularMovies(array $movies)
    {
        foreach ($movies as $index => $movie) {
            // Update or create the movie record
            $updatedMovie = Movie::updateOrCreate(
                ['tmdb_id' => $movie['id']],
                [
                    'title'        => $movie['title'] ?? 'Untitled',
                    'language'     => $movie['original_language'] ?? 'en',
                    'popularity'   => $movie['popularity'] ?? 0.0000,
                    'vote_average' => $movie['vote_average'] ?? 0.00,
                    'release_date' => $movie['release_date'] ?? now(),
                    'poster_path'  => $movie['poster_path'] ?? '/default.jpg',
                    'overview'     => $movie['overview'] ?? '',
                    'runtime'      => $movie['runtime'] ?? null,
                ]
            );

            // Update or create the popular movie record with ranking
            PopularMovie::updateOrCreate(
                ['id' => $index + 1],
                [
                  'movie_id' => $updatedMovie->id,
                  'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Update movie details and sync genres.
     * @param array $movie
     * @param array $genres
     */
    public function setMoieDetails(array $movie, array $genres)
    {
        // Update the movie record
        $updatedMovie = Movie::updateOrCreate(
                ['tmdb_id' => $movie['id']],
                [
                    'popularity'   => $movie['popularity'] ?? 0.0000,
                    'vote_average' => $movie['vote_average'] ?? 0.00,
                    'runtime'      => $movie['runtime'] ?? null,
                ]
            );

        // Sync genres
        $genreIds = Genre::whereIn('tmdb_id', array_column($genres, 'id'))->pluck('id')->toArray();
        $updatedMovie->genres()->sync($genreIds);
    }
}
