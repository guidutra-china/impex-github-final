<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionScheduleResource\Pages;
use App\Filament\Resources\ProductionScheduleResource\RelationManagers;
use App\Models\ProductionSchedule;
use App\Models\OrderItem; // Import OrderItem
use App\Models\Product; // Import Product
use App\Models\Order; // Import Order
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class ProductionScheduleResource extends Resource
{
    protected static ?string $model = ProductionSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Orders'; // Group with Order resource

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('order_item_id')
                    ->relationship('orderItem', 'id') // Relationship to OrderItem
                    ->getOptionLabelFromRecordUsing(fn (OrderItem $record) => "Order #{$record->order->order_number} - {$record->product->name}") // Custom label
                    ->searchable(['order.order_number', 'product.name']) // Search related fields
                    ->preload()
                    ->required(),
                DatePicker::make('scheduled_date')
                    ->required(),
                TextInput::make('quantity_scheduled')
                    ->required()
                    ->numeric()
                    ->minValue(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('orderItem.order.order_number')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orderItem.product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_scheduled')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Add delete action
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
            'index' => Pages\ListProductionSchedules::route('/'),
            'create' => Pages\CreateProductionSchedule::route('/create'),
            'edit' => Pages\EditProductionSchedule::route('/{record}/edit'),
        ];
    }
}

