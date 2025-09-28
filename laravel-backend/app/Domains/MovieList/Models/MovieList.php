<?php

namespace App\Domains\MovieList\Models;

use App\Models\User;
use App\Domains\Movie\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MovieList extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = [
        'name',
        'user_id',
        'description',
    ];

    /**
     * Get the movies that belong to the list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_list_movie');
    }

    /**
     * Get the user that owns the movie list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
