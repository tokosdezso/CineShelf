<?php

namespace App\Http\Controllers;

use App\Domains\PopularMovie\Models\PopularMovie;

class PopularMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $imageBaseUrl = config('services.tmdb.image_base_url') . '/w500';
            $limit = config('services.tmdb.popular_movie_display_number');
            $popularMovies = PopularMovie::with('movie')->limit($limit)->get()->map(function ($popular) use ($imageBaseUrl) {
                $movie = $popular->movie;

                // poster_path full URL
                $movie->poster_path = $movie->poster_path 
                    ? $imageBaseUrl . $movie->poster_path 
                    : null;

                $popular->movie = $movie;
                return $popular;
            });
            return response()->json($popularMovies);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }
}