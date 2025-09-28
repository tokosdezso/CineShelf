<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\MovieList\Models\MovieList;
use App\Domains\Movie\Models\Movie;
use App\Models\User;

class MovieListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $movies = Movie::pluck('id')->toArray();

        // Create 5 movie lists with random users and movies
        for ($i = 1; $i <= 5; $i++) {
            $user = $users->random();
            $movieList = MovieList::create([
                'name' => 'List ' . $i . ' by User ' . $user->name,
                'user_id' => $user->id,
                'description' => 'Random description for list ' . $i,
            ]);

            $randomMovies = collect($movies)->shuffle()->take(rand(5, 10))->toArray();
            $movieList->movies()->sync($randomMovies);
        }
    }
}
