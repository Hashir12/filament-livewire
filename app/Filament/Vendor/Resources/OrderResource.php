<?php

namespace App\Filament\Vendor\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Vendor\Resources\OrderResource\Pages;
use App\Filament\Vendor\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Order Details')->schema([
                        Forms\Components\TextInput::make('number')->default('OR-'.random_int(10000, 999999))->disabled()->dehydrated(),
                        Forms\Components\Select::make('customer_id')->relationship('customer','name')->searchable()->required(),
                        Forms\Components\Select::make('type')->options([
                            'pending' => OrderStatusEnum::PENDING->value,
                            'processing' => OrderStatusEnum::PROCESSING->value,
                            'completed' => OrderStatusEnum::COMPLETED->value,
                            'declined' => OrderStatusEnum::DECLINED->value,
                        ])->columnSpanFull(),
                        Forms\Components\MarkdownEditor::make('notes')->columnSpanFull()
                    ])->columns(2),

                    Forms\Components\Wizard\Step::make('Order Items')->schema([
                        Forms\Components\Repeater::make('items')->relationship()->schema([
                            Forms\Components\Select::make('product_id')->label('Product')->options(Product::query()->pluck('name','id')),
                            Forms\Components\TextInput::make('quantity')->numeric()->default(1)->required(),
                            Forms\Components\TextInput::make('unit_price')->label('Unit Price')->disabled()->dehydrated()->numeric()->required(),
                        ])->columns(3),
                    ])
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('customer.name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('total_price')->sortable()->searchable()->summarize([
                    Tables\Columns\Summarizers\Sum::make()->money()
                ]),
                Tables\Columns\TextColumn::make('created_at')->label('Order Date')->date()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
