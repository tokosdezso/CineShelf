<?php

namespace App\Domains\MovieList\Filament\Resources\MovieListResource\Pages;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Domains\MovieList\Filament\Resources\MovieListResource;
use Filament\Infolists\Components\TextEntry;

class ViewMovieLists extends ViewRecord
{
    /**
     * The resource the page belongs to.
     *
     * @var string
     */
    protected static string $resource = MovieListResource::class;

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
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('user.name')
                    ->label(__('User')),
                TextEntry::make('description')
                    ->label(__('Description')),
            ]);
    }
}