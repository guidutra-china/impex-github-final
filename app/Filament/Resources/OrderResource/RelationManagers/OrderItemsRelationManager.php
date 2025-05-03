<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use App\Models\Product;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)->schema([
                    Select::make('product_id')
                        ->relationship('product', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->distinct()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->afterStateUpdated(function ($state, callable $set, Get $get) {
                            $product = Product::find($state);
                            $price = $product ? $product->price : 0;
                            $set('price', $price);
                            // Use existing quantity if available (editing), else default to 1 (creating)
                            $quantity = $get('quantity') ?? 1;
                            $set('quantity', $quantity); // Ensure quantity is set
                            $set('total_price', $quantity * $price);
                        })
                        ->columnSpan(2),
                    TextInput::make('quantity')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->reactive()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $price = $get('price') ?? 0;
                            $set('total_price', $state * $price);
                        }),
                    TextInput::make('price')
                        ->label('Unit Price (Cents)')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $quantity = $get('quantity') ?? 0;
                            $set('total_price', $state * $quantity);
                        }),
                    TextInput::make('total_price')
                        ->label('Total Price (Cents)')
                        ->numeric()
                        ->required()
                        ->disabled()
                        ->dehydrated(true) // Ensure it's saved
                        ->prefix('$'),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name') // Use product name as title
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Unit Price')
                    ->money('USD', true) // Format as currency (assuming cents)
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('USD', true) // Format as currency (assuming cents)
                    ->numeric(0) // Ensure it's treated as numeric for potential sums
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
