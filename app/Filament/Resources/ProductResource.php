<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
//use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Basic')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\MarkdownEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('sku_client')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sku_supplier')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hscode')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ncm')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cost')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('currency')
                            ->maxLength(255),
                        Forms\Components\Select::make('family_id')
                            ->relationship('Family', 'name')
                            ->preload()
                            ->searchable()
                            ->nullable(),
                        Forms\Components\Select::make('client_id')
                            ->label('Client')
                            ->relationship(name: 'client', titleAttribute: 'name', modifyQueryUsing: fn ($query) => $query->clients())
                            ->searchable(),
                        Forms\Components\Select::make('supplier_id')
                        ->label('Supplier')
                        ->relationship('Supplier', 'name', modifyQueryUsing: fn ($query) => $query->suppliers())
                        ->searchable(),
                        Forms\Components\Select::make('tags')
                            ->relationship('tags', 'name')
                            ->preload(),
                    ]),

                    Tabs\Tab::make('Images')
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                                ->collection('products')
                                ->multiple()
                                ->reorderable()
                                ->downloadable()
                        ]),

                ])
                ->columns(2),

            ])
            ->columns(0);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                ->searchable()
                ->sortable()
                ->limit(30),
                Tables\Columns\TextColumn::make('sku_client')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku_supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hscode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ncm')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('family.name')
                    ->label('Family')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }
}
