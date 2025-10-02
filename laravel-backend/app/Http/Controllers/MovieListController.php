<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Domains\MovieList\Models\MovieList;
use Illuminate\Http\Request;

class MovieListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $withTrashed = $request->boolean('with_trashed', false);

        $query = MovieList::with('movies')->where('user_id', $user->id);

        // If with_trashed is true, include soft deleted movie lists
        if ($withTrashed) {
            $query->withTrashed();
        }

        $movieLists = $query->get();

        return response()->json($movieLists);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $movieList = MovieList::withTrashed()->with('movies')->where('user_id', $user->id)->find($id);

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

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $movieList = MovieList::where('user_id', $user->id)->find($id);

        if (!$movieList) {
            return response()->json(['message' => 'Movie list not found or it is not your list!'], 404);
        }

        $movieList->delete();
        return response()->json(['message' => 'Movie list deleted successfully!']);
    }
    
    /**
     * Restore the specified resource from storage.
     */
    public function update($id)
    {
        $user = Auth::user();
        $movieList = MovieList::withTrashed()->where('user_id', $user->id)->find($id);

        if (!$movieList) {
            return response()->json(['message' => 'Movie list not found or it is not your list!'], 404);
        }
        
        // Check if the movie list is soft deleted
        if ($movieList->trashed()) {
            $movieList->restore();
        }

        // If a movie ID is provided, remove that movie from the list
        if (request()->has('remove_movie_id')) {
            $movieId = request()->input('remove_movie_id'); 
            $movieList->movies()->detach($movieId);
        }

        // If a movie ID is provided, add that movie to the list
        if (request()->has('add_movie_id')) {
            $movieId = request()->input('add_movie_id'); 
            $movieList->movies()->syncWithoutDetaching($movieId);
        }

        $movieList->restore();
        return response()->json(['message' => 'Movie list updated successfully!']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $user = Auth::user();
        $movieList = MovieList::create([
            'name' => request()->name,
            'user_id' => $user->id,
        ]);

        if (!$movieList) {
            return response()->json(['message' => 'Error creating movie list!'], 500);
        }

        $movieList->load('movies');
        return response()->json($movieList);
    }
}
