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
}
