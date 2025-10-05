<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],


    // TMDB API configuration
    'tmdb' => [
        'api_read_access_token' => env('TMDB_API_READ_ACCESS_TOKEN'),
        'base_url' => env('TMDB_BASE_URL', 'https://api.themoviedb.org'),
        'popular_movies_uri' => env('TMDB_POPULAR_MOVIES_URI', '/3/movie/popular'),
        'movie_details_uri' => env('TMDB_MOVIE_DETAILS_URL', '/3/movie'),
        'image_base_url' => env('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p'),
        'popular_movie_display_number' => env('TMDB_POPULAR_MOVIE_NUMBER', '10'),
        'movie_search_uri' => env('TMDB_MOVIE_SEARCH_URI', '/3/search/movie'),
    ],

    'pagination' => [
        'movies_per_page' => env('MOVIES_PER_PAGE', '20'),
    ],

];
