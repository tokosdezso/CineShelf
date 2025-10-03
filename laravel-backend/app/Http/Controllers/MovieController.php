<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TMDBService;
use App\Domains\Genre\Models\Genre;
use App\Domains\Movie\Models\Movie;

class MovieController extends Controller
{

    // TMDB service
    protected TMDBService $tmdb;

/**
 * Constructor
 *
 * @param TMDBService $tmdb TMDB service object
 */
    public function __construct(TMDBService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $imageBaseUrl = config('services.tmdb.image_base_url') . '/w500';

        // Check if the movie is already in the database
        $movie = Movie::where('tmdb_id', $id)->first();

        if ($movie) {
            $movie->poster_path = $movie->poster_path 
                ? $imageBaseUrl . $movie->poster_path 
                : null;
            return response()->json($movie);
        }

        // Get movie details from TMDB
        try {
            $movie = $this->tmdb->getMovieDetails($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Movie not found in TMDB!'], 404);
        }

        if (!$movie) {
            return response()->json(['message' => 'Movie not found in TMDB!'], 404);
        }

        // Build an array like movie model
        $movieModel = [
            'id'           => null,
            'tmdb_id'      => $movie['id'],
            'title'        => $movie['title'] ?? 'Untitled',
            'language'     => $movie['original_language'] ?? 'en',
            'popularity'   => $movie['popularity'] ?? 0.0000,
            'vote_average' => $movie['vote_average'] ?? 0.00,
            'release_date' => $movie['release_date'] ?? now(),
            'poster_path'  => $movie['poster_path'] ?? '/default.jpg',
            'overview'     => $movie['overview'] ?? '',
            'runtime'      => $movie['runtime'] ?? null,
        ];

        $movieModel['poster_path'] = $movieModel['poster_path'] 
            ? $imageBaseUrl . $movieModel['poster_path'] 
            : null;

        return response()->json($movieModel);
    }

    // Store a movie in the database
    public function store()
    {
        $tmdb_id = request()->tmdb_id;

        // Check if the movie is already in the database
        $movie = Movie::where('tmdb_id', $tmdb_id)->first();

        if ($movie) {
            return response()->json($movie);
        }

        // Get movie details from TMDB
        $movie = $this->tmdb->getMovieDetails($tmdb_id);

        // Save movie to database
        $movieModel = Movie::create(
            [
                'tmdb_id'      => $movie['id'],
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

        // If there are new genres save them
        $existingIds = Genre::pluck('tmdb_id')->toArray();
        $newGenres = array_filter($movie['genres'], function($genre) use ($existingIds) {
            return !in_array($genre['id'], $existingIds);
        });
        foreach ($newGenres as $genre) {
            Genre::create([
                'tmdb_id' => $genre['id'],
                'name' => $genre['name'],
            ]);
        }

        // Sync genres
        $genreIds = Genre::whereIn('tmdb_id', array_column($movie['genres'], 'id'))->pluck('id')->toArray();
        $movieModel->genres()->sync($genreIds);

        return response()->json($movieModel);
    }

}
