<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Company Information')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('address'),
                    TextEntry::make('city'),
                    TextEntry::make('state'),
                    TextEntry::make('zip'),
                    TextEntry::make('country'),
                    TextEntry::make('phone'),
                    TextEntry::make('email'),
                    TextEntry::make('website'),
                    TextEntry::make('type'),
                ])
            ->columns(2),

            Section::make('Image Galery')
                ->schema([
                    SpatieMediaLibraryImageEntry::make('image')
                    ->collection('companies')

                ]),
        ]);
    }
}
