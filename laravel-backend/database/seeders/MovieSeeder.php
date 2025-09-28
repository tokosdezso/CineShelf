<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Movie\Models\Movie;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/movies.json'));
        $data = json_decode($json, true);

        // Insert or update movies from movies.json
        foreach ($data as $movie) {
            $movieModel = Movie::updateOrCreate(
                ['tmdb_id' => $movie['id']],
                [
                    'title'        => $movie['title'] ?? 'Untitled',
                    'language'     => $movie['original_language'] ?? 'en',
                    'popularity'   => $movie['popularity'] ?? 0.0000,
                    'vote_average' => $movie['vote_average'] ?? 0.00,
                    'release_date' => $movie['release_date'] ?? now(),
                    'poster_path'  => $movie['poster_path'] ?? '/default.jpg',
                    'overview'     => $movie['overview'] ?? '',
                    'runtime'      => $movie['runtime'] ?? null,
                ]
            );
            $genreIds = \App\Domains\Genre\Models\Genre::whereIn('tmdb_id', $movie['genre_ids'])->pluck('id')->toArray();
            $movieModel->genres()->sync($genreIds);
        }
    }
}
