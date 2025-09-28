<?php

namespace App\Domains\Movie\Filament\Resources\MovieResource\Pages;

use Filament\Actions;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Domains\Movie\Filament\Resources\MovieResource;

class ListMovies extends ListRecords
{
    /**
     * Get the resource for this page.
     */
    protected static string $resource = MovieResource::class;

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
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
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tmdb_id')
                    ->label(__('TMDB ID'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('release_date')
                    ->label(__('Release Date'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('language')
                    ->label(__('Language'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('popularity')
                    ->label(__('Popularity'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vote_average')
                    ->label(__('Vote Average'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('runtime')
                    ->label(__('Runtime (minutes)'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('overview')
                    ->label(__('Overview'))
                    ->limit(20),
                TextColumn::make('genres')
                    ->label(__('Genres'))
                    ->formatStateUsing(fn ($state, $record) => $record->genres->pluck('name')->join(', ')),
            ])->filters([
                SelectFilter::make('genres')
                    ->relationship('genres', 'name')
                    ->multiple()
                    ->label(__('Genre')),
                // Filter between two dates
                Filter::make('release_date_range')
                    ->form([
                        DatePicker::make('from')->label(__('From')),
                        DatePicker::make('to')->label(__('To')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->where('release_date', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->where('release_date', '<=', $data['to']));
                    })
                    ->label(__('Release Date Range')),
                // Filter vote_average between two numeric values (0-10)
                Filter::make('vote_average_range')
                    ->form([
                        TextInput::make('from')->label(__('Min rating'))->numeric(),
                        TextInput::make('to')->label(__('Max rating'))->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->where('vote_average', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->where('vote_average', '<=', $data['to']));
                    })
                    ->label(__('Rating Range')),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}