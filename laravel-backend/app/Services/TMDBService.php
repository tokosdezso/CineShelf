<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

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
     * @param int $page The page number
     * @return array The search results
     */
    public function getPopularMovies(int $page = 1): array
    {
        $cacheKey = 'tmdb-popular-movies-' . $page;
        // cache the popular movies for 1 hour
        return Cache::remember($cacheKey, 3600, function() use ($page) {
            $endpoint = config('services.tmdb.popular_movies_uri');
            return $this->getData($endpoint, ['page' => $page])['results'] ?? [];
        });
    }
    
    /**
     * Get movie details from TMDB.
     * @param int $movieTMDBId The movie TMDB ID
     * @return array The get result
     */
    public function getMovieDetails(int $movieTMDBId): array
    {
        $cacheKey = 'tmdb-movie-' . $movieTMDBId;
        // cache the movie details for 12 hours
        return Cache::remember($cacheKey, 43200, function() use ($movieTMDBId) {
            $endpoint = config('services.tmdb.movie_details_uri') . '/' . $movieTMDBId;
            return $this->getData($endpoint) ?? [];
        });
        
    }
    
    /**
     * Search for movies in TMDB.
     *
     * @param string $query The search query
     * @param int $page The page number
     * @return array The search results
     */

    public function getMovies(string $query, int $page): array
    {
        $cacheKey = 'tmdb-movies-' . $query . '-' . $page;
        // cache the search results for 15 minutes
        return Cache::remember($cacheKey, 900, function() use ($query, $page) {
            $endpoint = config('services.tmdb.movie_search_uri');
            return $this->getData($endpoint, ['query' => $query, 'page' => $page]) ?? []; 
        });
    }
}
