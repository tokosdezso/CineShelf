<?php

namespace App\Domains\Genre\Filament\Resources\GenreResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use App\Domains\Genre\Filament\Resources\GenreResource;

class CreateGenre extends CreateRecord
{
    protected static string $resource = GenreResource::class;

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
                TextInput::make('name')
                    ->required()
                    ->label(__('Genre Name'))
                    ->required(),
                TextInput::make('tmdb_id')
                    ->label(__('TMDB ID'))
                    ->unique(ignoreRecord: true)
                    ->required(),
            ]);
    }
}
