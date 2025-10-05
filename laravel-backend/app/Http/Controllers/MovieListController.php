<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieProcessor;
use Illuminate\Support\Facades\Auth;
use App\Domains\MovieList\Models\MovieList;

class MovieListController extends Controller
{
    // Movie service
    private MovieProcessor $movieProcessor;

    /**
     * Constructs a new MovieListController instance.
     *
     * @param MovieProcessor $movieProcessor The movie service object.
     */
    public function __construct(MovieProcessor $movieProcessor)
    {
        $this->movieProcessor = $movieProcessor;
    }

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
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $filters = [
            'with_genres'       => $request->input('with_genres'),
            'vote_average_gte'  => $request->input('vote_average_gte'),
            'vote_average_lte'  => $request->input('vote_average_lte'),
            'release_date_gte'  => $request->input('release_date_gte'),
            'release_date_lte'  => $request->input('release_date_lte'),
            'sort_by'           => $request->input('sort_by'),
            'page'              => $request->input('page', 1),
        ];
        $filters = array_filter($filters);

        $movieList = MovieList::withTrashed()->where('user_id', $user->id)->find($id);

        if (!$movieList) {
            return response()->json(['message' => 'Movie list not found or it is not your list!'], 404);
        }

        try{
            $movies = $this->movieProcessor->movieListMovies($movieList, $filters);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing movies in list!'], $e->getCode());
        }
        return response()->json([
            'id' => $movieList->id,
            'name' => $movieList->name,
            'movies' => $movies,
        ]);
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
