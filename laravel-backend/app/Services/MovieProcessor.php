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

    /**
     * Fetch movies from a movie list with filters applied.
     * @param \App\Domains\MovieList\Models\MovieList $movieList
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function movieListMovies($movieList, $filters)
    {
        $moviesQuery = $movieList->movies();

        // Apply filters
        if (isset($filters['with_genres'])) {
            $genreIds = explode(',', $filters['with_genres']);
            $moviesQuery->whereHas('genres', function($q) use ($genreIds) {
                $q->whereIn('genres.id', $genreIds);
            });
        }

        if (isset($filters['vote_average_gte'])) {
            $moviesQuery->where('movies.vote_average', '>=', (float)$filters['vote_average_gte']);
        }

        if (isset($filters['vote_average_lte'])) {
            $moviesQuery->where('movies.vote_average', '<=', (float)$filters['vote_average_lte']);
        }

        if (isset($filters['release_date_gte'])) {
            $moviesQuery->where('release_date', '>=', $filters['release_date_gte']);
        }

        if (isset($filters['release_date_lte'])) {
            $moviesQuery->where('release_date', '<=', $filters['release_date_lte']);
        }

        if (isset($filters['sort_by'])) {
            [$column, $direction] = explode('.', $filters['sort_by']);
            $moviesQuery->orderBy($column, $direction);
        }

        // Apply pagination
        $perPage = config('services.pagination.movies_per_page');
        $movies = $moviesQuery->paginate($perPage, ['*'], 'page', $filters['page'] ?? 1);

        //image TMDB base URL from config
        $imageBaseUrl = config('services.tmdb.image_base_url') . '/w500';
        $movies->getCollection()->transform(function ($movie) use ($imageBaseUrl) {
            $movie->poster_path = $movie->poster_path 
                ? $imageBaseUrl . $movie->poster_path 
                : null;

            return $movie;
        });

        return $movies;
    }
}
