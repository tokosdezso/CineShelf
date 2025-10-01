<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Domains\MovieList\Models\MovieList;

class MovieListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $movieLists = MovieList::with('movies')
            ->where('user_id', $user->id)
            ->get();
        return response()->json($movieLists);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $movieList = MovieList::with('movies')->where('user_id', $user->id)->find($id);

        if (!$movieList) {
            return response()->json(['message' => 'Movie list not found or it is not your list!'], 404);
        }

        //image TMDB base URL from config
        $imageBaseUrl = config('services.tmdb.image_base_url') . '/w500';
        $movieList->movies->transform(function ($movie) use ($imageBaseUrl) {
            // poster_path full URL
            $movie->poster_path = $movie->poster_path 
                ? $imageBaseUrl . $movie->poster_path 
                : null;

            return $movie;
        });
        return response()->json($movieList);
    }
}
