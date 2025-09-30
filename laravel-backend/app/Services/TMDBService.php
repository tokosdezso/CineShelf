<?php

namespace App\Services;

use GuzzleHttp\Client;

class TMDBService
{
    /** @var Client */
    protected Client $client;

    /**
     * TMDBService constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.tmdb.base_url'),
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.tmdb.api_read_access_token'),
            ],
        ]);
    }

    /**
     * Generic method to fetch data from TMDB API.
     */
    private function getData(string $endpoint, array $query = []): ?array
    {
        try {
            $response = $this->client->request('GET', $endpoint, [
                'query' => $query
            ]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
            return [];
        } catch (\Exception $e) {
            throw new \Exception('TMDB API request failed with status code: ' . $e->getCode());
        }
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
