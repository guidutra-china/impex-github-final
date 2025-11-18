<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Company;
use App\Models\Product;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get; // Import Get
use Illuminate\Support\Collection; // For validation
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\TextInput::make('order_number')
//                    ->required()
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('client_company_id')
//                    ->required()
//                    ->numeric(),
//                Forms\Components\TextInput::make('supplier_company_id')
//                    ->numeric(),
//                Forms\Components\TextInput::make('payment_id')
//                    ->numeric(),
//                Forms\Components\DatePicker::make('order_date')
//                    ->required(),
//                Forms\Components\TextInput::make('origen')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('destination')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('client_number')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('supplier_number')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('total_price')
//                    ->numeric()
//                    ->default(0),
//                Forms\Components\TextInput::make('discount')
//                    ->numeric()
//                    ->default(0),
//                Forms\Components\TextInput::make('net_weight')
//                    ->numeric()
//                    ->default(0),
//                Forms\Components\TextInput::make('gross_weight')
//                    ->numeric()
//                    ->default(0),
            ]);
    }

    public static function getStep1FormSchema(): array
    {
        return [
            // Seção 1: RFQ Information
            Section::make('RFQ Information')
                ->description('Request for Quotation details')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('order_number')
                            ->label('Order Number')
                            ->required()
                            ->maxLength(255)
                            ->unique(Order::class, 'order_number', ignoreRecord: true),

                        DatePicker::make('order_date')
                            ->required()
                            ->default(now()),

                        TextInput::make('client_number')
                            ->label('Client PO Number')
                            ->maxLength(255),

                        TextInput::make('supplier_number')
                            ->label('Supplier Order Number')
                            ->maxLength(255),
                    ]),
                ])
                ->collapsible(),

            // Seção 2: Customer & Currency
            Section::make('Customer & Currency')
                ->description('Client, supplier and payment information')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('client_company_id')
                            ->label('Client')
                            ->relationship('client', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereIn('type', ['client', 'both']))
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('supplier_company_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereIn('type', ['supplier', 'both']))
                            ->searchable()
                            ->preload(),

                        Select::make('payment_id')
                            ->label('Payment Method')
                            ->relationship('paymentMethod', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                ])
                ->collapsible(),

            // Seção 3: Logistics
            Section::make('Logistics')
                ->description('Origin and destination information')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('origen')
                            ->label('Origin')
                            ->maxLength(255)
                            ->placeholder('Enter origin location'),

                        TextInput::make('destination')
                            ->maxLength(255)
                            ->placeholder('Enter destination location'),
                    ]),
                ])
                ->collapsible(),
        ];
    }

    public static function getStep2FormSchema(): array
    {
        return [
            Repeater::make('orderItems')
                ->relationship()
                ->label('Products')
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
                            ->afterStateUpdated(function ($state, callable $set, Get $get) { // Added Get
                                $product = Product::find($state);
                                $price = $product ? $product->price : 0;
                                $set('price', $price);
                                $set('quantity', 1); // Default quantity to 1
                                $set('total_price', $price); // Update total price for quantity 1
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
                            ->label('Unit Price (Cents)') // Clarify unit
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $quantity = $get('quantity') ?? 0;
                                $set('total_price', $state * $quantity);
                            }),
                        TextInput::make('total_price')
                            ->label('Total Price (Cents)') // Clarify unit
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated(true)
                            ->prefix('$'), // Display prefix, but value is integer cents
                    ]),
                ])
                ->defaultItems(1)
                ->addActionLabel('Add Product')
                ->reorderableWithButtons()
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => Product::find($state['product_id'])?->name ?? null), // Set item label based on selected product
        ];
    }
    // Added getStep3FormSchema method for production scheduling
//    public static function getStep3FormSchema(): array
//    {
//        return [
//            // Use Repeater component directly
//            Repeater::make("orderItems")
////                ->relationship() // Load existing items
//                ->label("Production Schedule per Product")
//                ->addable(false) // Don"t allow adding/deleting items here
//                ->deletable(false)
//                ->reorderable(false)
//                ->collapsible()
//                ->itemLabel(fn (array $state): ?string => Product::find($state["product_id"])?->name ?? null)
//                ->schema([
//                    // Use Placeholder component directly
//                    Placeholder::make("product_info")
//                        ->label("Product")
//                        ->content(function (Get $get): string {
//                            $product = Product::find($get("product_id"));
//                            $quantity = $get("quantity");
//                            return $product ? "{$product->name} (Ordered: {$quantity})" : "N/A";
//                        }),
//                    // Nested Repeater for Production Schedules per item
//                    // Use Repeater component directly
//                    Repeater::make("productionSchedules")
////                        ->relationship()
//                        ->label("Schedule")
//                        ->schema([
//                            // Use DatePicker component directly
//                            DatePicker::make("scheduled_date")
//                                ->required()
//                                ->label("Date"),
//                            // Use TextInput component directly
//                            TextInput::make("quantity_scheduled")
//                                ->numeric()
//                                ->required()
//                                ->minValue(1)
//                                ->label("Quantity"),
//                        ])
//                        ->columns(2)
//                        ->addActionLabel("Add Schedule Date")
//                        ->reorderableWithButtons()
//                        ->collapsible()
//                        ->collapsed()
//                        ->defaultItems(0)
//                        ->rule(static function (Get $get): \Closure { // Custom rule for total scheduled quantity
//                            return static function (string $attribute, $value, \Closure $fail) use ($get) {
//                                // $get("../../quantity") gets the quantity from the parent repeater item
//                                $totalOrdered = (int) $get("../../quantity");
//                                if ($totalOrdered <= 0) return; // Don"t validate if parent quantity is invalid
//
//                                $totalScheduled = collect($value)->sum("quantity_scheduled");
//
//                                if ($totalScheduled > $totalOrdered) {
//                                    $fail("The total scheduled quantity ({$totalScheduled}) cannot exceed the total ordered quantity ({$totalOrdered}).");
//                                }
//                            };
//                        }),
//                ])
//        ];
//    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make("orderItems_sum_total_price") // Use a different name
                ->sum("orderItems", "total_price")
                    ->label("Total Price") // Updated label
                    ->money("USD", true) // Format as currency (assuming cents)
                    ->numeric(0) // Ensure numeric alignment if needed
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add filters if needed
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
            RelationManagers\OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
