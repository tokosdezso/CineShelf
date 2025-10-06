<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Exceptions\ApiResponseException;

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
                $data = json_decode($response->getBody(), true);
                if (is_array($data)) {
                    return $data;
                }
                throw new ApiResponseException('Invalid response from TMDB API', 502);
            }
            throw new ApiResponseException('TMDB API returned non-200 status', $response->getStatusCode());
        } catch (\Exception $e) {
            throw new ApiResponseException('TMDB API request failed: ' . $e->getMessage(), $e->getCode() ?: 500);
        }
    }
}