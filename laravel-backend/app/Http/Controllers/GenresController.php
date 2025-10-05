<?php

namespace App\Http\Controllers;

use App\Domains\Genre\Models\Genre;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $genres = Genre::all();
        } catch (\Exception $e) {
            return response()->json(['message' => 'No genres found!'], $e->getCode());
        }

        return response()->json($genres);
    }
}
