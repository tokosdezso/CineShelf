<?php

namespace App\Domains\PopularMovie\Filament\Resources\PopularMovieResource\Pages;

use Filament\Actions;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ListRecords;
use App\Domains\PopularMovie\Filament\Resources\PopularMovieResource;

class ListPopularMovies extends ListRecords
{
    /**
     * Get the resource for this page.
     */
    protected static string $resource = PopularMovieResource::class;

    /**
     * Table view.
     *
     * @param  \Filament\Tables\Table  $table
     *
     * @return \Filament\Tables\Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('Place'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('movie.title')
                    ->label(__('Movie Title'))
                    ->formatStateUsing(fn ($state, $record) => $record->movie?->title ?? '')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('movie.popularity')
                    ->label(__('Movie Popularity'))
                    ->formatStateUsing(fn ($state, $record) => $record->movie?->popularity ?? '')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->sortable()
                    ->searchable(),
            ]);
    }
}
