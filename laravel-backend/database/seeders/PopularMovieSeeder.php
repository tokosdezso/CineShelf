<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Movie\Models\Movie;
use App\Domains\PopularMovie\Models\PopularMovie;

class PopularMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = Movie::limit(20)->get();

        foreach ($movies as $index => $movie) {
            PopularMovie::updateOrCreate(
                ['id' => $index + 1],
                [
                  'movie_id' => $movie->id,
                  'updated_at' => now(),
                ]
            );
        }
    }
}
