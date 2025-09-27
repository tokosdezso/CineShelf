<?php

namespace App\Domains\Genre\Filament\Resources\GenreResource\Pages;

use Filament\Actions;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use App\Domains\Genre\Filament\Resources\GenreResource;

class ListGenres extends ListRecords
{
    protected static string $resource = GenreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

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
                TextColumn::make('name')
                    ->label(__('Genre Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tmdb_id')
                    ->label(__('TMDB ID'))
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
