<?php

namespace App\Domains\Movie\Models;

use App\Domains\Genre\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = [
        'title',
        'tmdb_id',
        'popularity',
        'vote_average',
        'release_date',
        'poster_path',
        'overview',
        'runtime',
        'language',
    ];

    /**
     * Get the genres that belong to the movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }
}
