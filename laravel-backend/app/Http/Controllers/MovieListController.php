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
        $movieLists = MovieList::withTrashed()->with('movies')
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

        $movieList->restore();
        return response()->json(['message' => 'Movie list updated successfully!']);
    }
}
