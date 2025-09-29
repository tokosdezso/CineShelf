<?php

namespace App\Domains\PopularMovie\Filament\Resources\PopularMovieResource\Pages;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Domains\PopularMovie\Filament\Resources\PopularMovieResource;

class ViewPopularMovies extends ViewRecord
{
    /**
     * The resource the page belongs to.
     *
     * @var string
     */
    protected static string $resource = PopularMovieResource::class;

    /**
     * The detailed view
     *
     * @param \Filament\Infolists\Infolist $infolist
     *
     * @return Filament\Infolists\Infolist
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('id')
                    ->label(__('Place')),
                TextEntry::make('updated_at')
                    ->label(__('Updated At')),
            ]);
    }
}