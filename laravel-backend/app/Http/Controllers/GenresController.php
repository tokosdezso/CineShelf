<?php

namespace App\Http\Controllers;

use App\Domains\Genre\Models\Genre;
use App\Exceptions\ApiResponseException;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $genres = Genre::all();
            return response()->json($genres);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }
}
