<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieProcessor;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MovieFilterRequest;
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
        $this->authorize('viewAny', MovieList::class);

        try {
            $request->validate([
                'with_trashed' => 'nullable|boolean',
            ]);

            $withTrashed = $request->boolean('with_trashed', false);

            $user = Auth::user();
            $query = MovieList::with('movies')->where('user_id', $user->id);

            // If with_trashed is true, include soft deleted movie lists
            if ($withTrashed) {
                $query->withTrashed();
            }

            $movieLists = $query->get();
            return response()->json($movieLists);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MovieFilterRequest $request, $id)
    {
        try {

            $filters = $request->validated();
            $filters = array_filter($filters);

             // Find the movie list including soft deleted ones
            $movieList = MovieList::withTrashed()->find($id);

            $this->authorize('view', $movieList);

            if (!$movieList) {
                return response()->json(['message' => 'Movie list not found or it is not your list!'], 404);
            }

            $movies = $this->movieProcessor->movieListMovies($movieList, $filters);
            return response()->json([
                'id' => $movieList->id,
                'name' => $movieList->name,
                'movies' => $movies,
                'deleted_at' => $movieList->deleted_at,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $movieList = MovieList::find($id);

            $this->authorize('delete', $movieList);
            
            if (!$movieList) {
                return response()->json(['message' => 'Movie list not found or it is not your list!'], 404);
            }

            $movieList->delete();
            return response()->json(['message' => 'Movie list deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Restore the specified resource from storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $movieList = MovieList::withTrashed()->find($id);

            $this->authorize('update', $movieList);

            if (!$movieList) {
                return response()->json(['message' => 'Movie list not found or it is not your list!'], 404);
            }

            $validated = $request->validate([
                'remove_movie_id' => 'nullable|integer|exists:movies,id',
                'add_movie_id'    => 'nullable|integer|exists:movies,id',
            ]);
            
            // Check if the movie list is soft deleted
            if ($movieList->trashed()) {
                $movieList->restore();
            }

            // If a movie ID is provided, remove that movie from the list
            if (!empty($validated['remove_movie_id'])) {
                $movieId = $validated['remove_movie_id']; 
                $movieList->movies()->detach($movieId);
                return response()->json(['message' => 'Movie list updated successfully, remove movie!']);
            }

            // If a movie ID is provided, add that movie to the list
            if (!empty($validated['add_movie_id'])) {
                $movieId = $validated['add_movie_id']; 
                $movieList->movies()->syncWithoutDetaching($movieId);
                return response()->json(['message' => 'Movie list updated successfully, add movie!']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $this->authorize('create', MovieList::class);

        try {
            $validated = request()->validate([
                'name' => 'required|string|max:255',
            ]);
            $user = Auth::user();
            $movieList = MovieList::create([
                'name' => $validated['name'],
                'user_id' => $user->id,
            ]);

            if (!$movieList) {
                return response()->json(['message' => 'Error creating movie list!'], 500);
            }

            $movieList->load('movies');
            return response()->json($movieList);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }
}
