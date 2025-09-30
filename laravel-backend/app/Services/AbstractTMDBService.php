<?php

namespace App\Services;

use GuzzleHttp\Client;

abstract class AbstractTMDBService
{
    /** @var Client */
    protected Client $client;

    /**
     * AbstractTMDBService constructor.
     */
    public function __construct(string $baseUri, array $headers = [])
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => $headers,
        ]);
    }

    /**
     * Generic method to fetch data from TMDB API.
     */
    protected function getData(string $endpoint, array $query = []): ?array
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
}