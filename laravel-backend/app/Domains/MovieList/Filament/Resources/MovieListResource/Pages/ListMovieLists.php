<?php

namespace App\Domains\MovieList\Filament\Resources\MovieListResource\Pages;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use App\Domains\MovieList\Filament\Resources\MovieListResource;

class ListMovieLists extends ListRecords
{
    /**
     * Get the resource for this page.
     */
    protected static string $resource = MovieListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
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
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label(__('User'))
                    ->formatStateUsing(fn ($state, $record) => $record->user?->name ?? '')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
}
