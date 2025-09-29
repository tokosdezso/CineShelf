<?php

namespace App\Domains\PopularMovie\Filament\Resources\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MovieRelationManager extends RelationManager
{
    /**
     * The name of the relationship that this relation manager is for.
     *
     * @var string
     */
    protected static string $relationship = 'movie';

    /**
     * The table view for the relation manager.
     *
     * @var Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Movie Title'))
                    ->limit(25),
                TextColumn::make('overview')
                    ->label(__('Overview'))
                    ->limit(50),
                TextColumn::make('popularity')
                    ->label(__('Popularity')),
                TextColumn::make('vote_average')
                    ->label(__('Rating')),
            ]);
    }

    /**
     * The title of the relation manager.
     *
     * @param Model $ownerRecord
     * @param string $pageClass
     * @return string
     */
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Movie');
    }
}
