<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieListController;
use App\Http\Controllers\PopularMovieController;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::apiResource('/popular-movies', PopularMovieController::class)
            ->only(['index']);

        Route::apiResource('/movie-lists', MovieListController::class)
            ->only(['index']);
    });
