<?php

namespace App\Domains\MovieList\Filament\Resources;

use Filament\Resources\Resource;
use App\Domains\MovieList\Models\MovieList;
use App\Domains\MovieList\Filament\Resources\MovieListResource\Pages\ListMovieLists;
use App\Domains\MovieList\Filament\Resources\MovieListResource\Pages\ViewMovieLists;
use App\Domains\MovieList\Filament\Resources\RelationManagers\MovieRelationManager;

class MovieListResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    protected static ?string $model = MovieList::class;

    /**
     * The heroicon name of the resource.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Get the relation managers available for the resource.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            MovieRelationManager::class,
        ];
    }

/**
 * Get the pages available for the resource.
 *
 * @return array
 */
    public static function getPages(): array
    {
        return [
            'index' => ListMovieLists::route('/'),
            'view' => ViewMovieLists::route('/{record}'),
        ];
    }
}
