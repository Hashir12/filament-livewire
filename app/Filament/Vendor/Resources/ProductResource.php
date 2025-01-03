<?php

namespace App\Filament\Vendor\Resources;

use App\Enums\ProductTypeEnum;
use App\Filament\Vendor\Resources\ProductResource\Pages;
use App\Filament\Vendor\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?int $navigationSort = 0;
    protected static int $globalSearchResultsLimit = 20;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $recordTitleAttribute = "name";

    public static function getGloballySearchableAttributes () : array
    {
        return ['name','slug','description'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Brand' => $record->brand->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->unique()
                            ->afterStateUpdated(function (string $operation,$state, Forms\Set $set) {
                                if ($operation != 'create') {
                                    return;
                                }
                                $set('slug',Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class,'slug',ignoreRecord: true),
                        Forms\Components\MarkdownEditor::make('description')->columnSpan(2),
                    ])->columns(2),

                    Forms\Components\Section::make('Pricing & Inventory')->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU (Stock Keeping Unit)')->unique()->required(),
                        Forms\Components\TextInput::make('price')->numeric()->rules([
                            'regex:/^\d{1,6}(\.\d{0,2})?$/'
                        ])->minValue(0)->required(),
                        Forms\Components\TextInput::make('quantity')->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\Select::make('type')->options([
                            'downloadable' =>  ProductTypeEnum::DOWNLOADABLE->value,
                            'deliverable' =>  ProductTypeEnum::DELIVERABLE->value,
                        ])->required()
                    ])->columns(2)

                ]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('status')->schema([
                        Forms\Components\Toggle::make('is_visible')->label('Visibility')->helperText('Enable or disable product visibility')->default(true),
                        Forms\Components\Toggle::make('is_featured')->label('Featured')->helperText('Enable or disable product featured status')->default(true),
                        Forms\Components\DatePicker::make('published_at')->label('Availability')->default(now())
                            ->columnSpan(2),
                    ])->columns(2),

                    Forms\Components\Section::make("Image")->schema([
                        Forms\Components\FileUpload::make('image')->directory('form-attachments')->preserveFilenames()->image()->imageEditor(),
                    ])->collapsible(),

                    Forms\Components\Section::make("Associations")->schema([
                        Forms\Components\Select::make('brand_id')->relationship('brand','name'),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('brand.name')->searchable()->sortable()->toggleable(),
                Tables\Columns\IconColumn::make('is_visible')->toggleable()->sortable()->label('Visibility')->boolean(),
                Tables\Columns\TextColumn::make('price')->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('published_at')->date()->sortable(),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visible')
                    ->boolean()
                    ->trueLabel('Only Visible Products')
                    ->falseLabel('Only Hidden Products')
                    ->native(false),
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand','name')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
