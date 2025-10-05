<?php

namespace App\Domains\MovieList\Policies;

use App\Models\User;
use App\Domains\MovieList\Models\MovieList;

class MovieListPolicy
{
    /**
     * Determine if the given user can view any movie list.
     *
     * This policy returns true if the user is not null, meaning that
     * the user is authenticated.
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user !== null;
    }

    /**
     * Determine if the given user can view a movie list.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Domains\MovieList\Models\MovieList  $movieList
     * @return bool
     */
    public function view(User $user, MovieList $movieList)
    {
        return $user->id === $movieList->user_id;
    }

    /**
     * Determine if the given user can update a movie list.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Domains\MovieList\Models\MovieList  $movieList
     * @return bool
     */
    public function update(User $user, MovieList $movieList)
    {
        return $user->id === $movieList->user_id;
    }

    /**
     * Determine if the given user can delete a movie list.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Domains\MovieList\Models\MovieList  $movieList
     * @return bool
     */
    public function delete(User $user, MovieList $movieList)
    {
        return $user->id === $movieList->user_id;
    }

    /**
     * Determine if the given user can create a movie list.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user !== null;
    }
}