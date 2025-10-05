<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Domains\MovieList\Models\MovieList;
use App\Domains\MovieList\Policies\MovieListPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(MovieList::class, MovieListPolicy::class);
    }
}
