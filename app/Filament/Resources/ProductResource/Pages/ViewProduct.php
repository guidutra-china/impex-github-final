<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Product Information')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('description'),
                    TextEntry::make('sku_client'),
                    TextEntry::make('sku_supplier'),
                    TextEntry::make('hscode'),
                    TextEntry::make('ncm'),
                    TextEntry::make('cost'),
                    TextEntry::make('price'),
                    TextEntry::make('currency'),
                    TextEntry::make('family.name'),
                ])
                ->columns(2),

            Section::make('Image Galery')
                ->schema([
                    SpatieMediaLibraryImageEntry::make('image')
                        ->collection('products')
                ]),
        ]);
    }



}
