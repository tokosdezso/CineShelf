<?php

namespace App\Services;

class TMDBService extends AbstractTMDBService
{
    /**
     * TMDBService constructor.
     */
    public function __construct()
    {
        parent::__construct(
            config('services.tmdb.base_url'),
            [
                'Authorization' => 'Bearer ' . config('services.tmdb.api_read_access_token'),
            ],
        );
    }

    /**
     * Fetch popular movies from TMDB.
     */
    public function getPopularMovies(int $page = 1): array
    {
        $endpoint = config('services.tmdb.popular_movies_uri');
        return $this->getData($endpoint, ['page' => $page])['results'] ?? [];
    }
    
    /**
     * Get movie details from TMDB.
     */
    public function getMovieDetails(int $movieTMDBId): array
    {
        $endpoint = config('services.tmdb.movie_details_uri') . '/' . $movieTMDBId;
        return $this->getData($endpoint) ?? [];
    }
}
