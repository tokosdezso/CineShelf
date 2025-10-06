<?php

namespace Tests\Unit;

use App\Services\TMDBService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

// Note: These tests mock the Guzzle client to avoid making real HTTP requests to the TMDB API.
// It is not possible to use Http::fake() here because the TMDBService uses Guzzle directly.
class TMDBServiceTest extends TestCase
{
    // Reset the cache before each test
    public function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /**
     * Tests that getPopularMovies returns data from the API.
     *
     * This test mocks the Guzzle client to return a successful response
     * with a single movie result. It then verifies that the returned
     * data is an array and that the titles of the movies matche
     * the expected values.
     */
    public function test_get_popular_movies_returns_data_from_api()
    {
        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->andReturn(new GuzzleResponse(200, [], json_encode(['results' => [
                ['id' => 1, 'title' => 'Popular Movie'],
                ['id' => 2, 'title' => 'Another Movie']
            ]])));

        $service = app(TMDBService::class);
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $result = $service->getPopularMovies();
        $this->assertIsArray($result);
        $this->assertEquals('Popular Movie', $result[0]['title']);
        $this->assertEquals('Another Movie', $result[1]['title']);
    }

    /**
     * Tests that getMovieDetails returns data from the API.
     *
     * This test mocks the Guzzle client to return a successful response
     * with a single movie result. It then verifies that the returned
     * data matches the expected values.
     */
    public function test_get_movie_details_returns_data_from_api()
    {
        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->andReturn(new GuzzleResponse(200, [], json_encode(
                [
                    'id' => 42,
                    'title' => 'Movie Title',
                    'release_date' => '2023-01-01',
                    'overview' => 'A great movie about things.',
                    'poster_path' => '/poster.jpg',
                    'running_time' => 120,
                    'language' => 'en',
                    'popularity' => 150.5,
                    'vote_average' => 8.5,
                    'genres' => [ 
                        ['id' => 28, 'name' => 'Action'],
                        ['id' => 12, 'name' => 'Adventure'],
                    ]
                ])));

        $service = app(TMDBService::class);
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $result = $service->getMovieDetails(42);
        $this->assertIsArray($result);
        $this->assertEquals('Movie Title', $result['title']);
        $this->assertEquals('2023-01-01', $result['release_date']);
        $this->assertEquals(8.5, $result['vote_average']);
        $this->assertEquals('A great movie about things.', $result['overview']);
        $this->assertEquals('/poster.jpg', $result['poster_path']);
        $this->assertEquals(120, $result['running_time']);
        $this->assertEquals('en', $result['language']);
        $this->assertEquals(150.5, $result['popularity']);
        $this->assertEquals([
            ['id' => 28, 'name' => 'Action'],
            ['id' => 12, 'name' => 'Adventure'],
        ], $result['genres']);
    }


    /**
     * Tests that getMovies returns data from the API.
     *
     * This test mocks the Guzzle client to return a successful response
     * with a single movie result. It then verifies that the returned
     * data is an array, and that the titles of the movies match the
     * expected values.
     */
    public function test_get_movies_search_returns_data_from_api()
    {
        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->andReturn(new GuzzleResponse(200, [], json_encode(['results' => [['id' => 2, 'title' => 'Searched Movie']], 'total_pages' => 1, 'total_results' => 11, 'page' => 1])));

        $service = app(TMDBService::class);
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $result = $service->getMovies('test', 1);
        $this->assertIsArray($result['results']);
        $this->assertEquals('Searched Movie', $result['results'][0]['title']);
        $this->assertEquals(1, $result['total_pages']);
        $this->assertEquals(11, $result['total_results']);
        $this->assertEquals(1, $result['page']);
    }

    /**
     * Tests that the TMDBService class handles API errors gracefully.
     *
     * This test mocks the Guzzle client to throw an exception when
     * the request method is called. It then verifies that calling
     * getPopularMovies() results in the expected exception being thrown.
     */
    public function test_handles_api_error_gracefully()
    {
        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->andThrow(new \Exception('TMDB API request failed', 500));

        $service = app(TMDBService::class);
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        $this->expectException(\Exception::class);
        $service->getPopularMovies();
    }
}
