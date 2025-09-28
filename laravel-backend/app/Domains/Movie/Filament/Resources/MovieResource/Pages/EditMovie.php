<?php

namespace App\Domains\Movie\Filament\Resources\MovieResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\DatePicker;
use App\Domains\Movie\Filament\Resources\MovieResource;

class EditMovie extends EditRecord
{
    protected static string $resource = MovieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

     /**
     * Create view.
     *
     * @param  \Filament\Forms\Form  $form
     *
     * @return \Filament\Forms\Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),
                TextInput::make('runtime')
                    ->numeric()
                    ->label('Runtime (minutes)'),
                TextInput::make('tmdb_id')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->label('TMDB ID'),
                TextInput::make('popularity')
                    ->numeric()
                    ->label('Popularity'),
                TextInput::make('vote_average')
                    ->numeric()
                    ->label('Vote Average'),
                DatePicker::make('release_date')
                    ->label('Release Date'),
                TextInput::make('poster_path')
                    ->label('Poster Path'),
                TextInput::make('overview')
                    ->label('Overview')
                    ->maxLength(2000),
                TextInput::make('language')
                    ->label('Language')
                    ->default('en'),
                Select::make('genres')
                    ->multiple()
                    ->relationship('genres', 'name')
                    ->preload()
                    ->searchable()
                    ->label('Genres'),
            ]);
    }
}
