<?php

namespace App\Domains\PopularMovie\Filament\Resources;

use Filament\Resources\Resource;
use App\Domains\PopularMovie\Models\PopularMovie;
use App\Domains\PopularMovie\Filament\Resources\PopularMovieResource\Pages;
use App\Domains\PopularMovie\Filament\Resources\RelationManagers\MovieRelationManager;

class PopularMovieResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    protected static ?string $model = PopularMovie::class;

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
            'index' => Pages\ListPopularMovies::route('/'),
            'view' => Pages\ViewPopularMovies::route('/{record}'),
        ];
    }
}
