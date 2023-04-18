<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DishResource\Pages;
use App\Models\Category;
use App\Models\Dish;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DishResource extends Resource
{
    protected static ?string $model = Dish::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                static::getFormGlobalSchema() 
            )->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('dishes-images')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('eur')
                    ->toggleable(),

                Tables\Columns\ToggleColumn::make('visibility_restaurant_menu')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDishes::route('/'),
            'create' => Pages\CreateDish::route('/create'),
            'edit' => Pages\EditDish::route('/{record}/edit'),
        ];
    }

    public static function getFormGlobalSchema(): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\Select::make('category_id')
                                ->searchable()
                                ->required()
                                ->options(Category::pluck('name', 'id'))
                                ->preload(),
                            Forms\Components\RichEditor::make('description')
                                ->disableAllToolbarButtons()
                                ->enableToolbarButtons([ 'bold', 'italic', 'strike' ])
                                ->required()
                                ->columnSpan('full')
                        ])->columns(2),
                ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('image')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->collection('dishes-images')
                                ->image()
                                ->disableLabel()
                        ]),
                    Forms\Components\Section::make('extra')
                        ->schema([
                            Forms\Components\Toggle::make('visibility_restaurant_menu')
                            ->default(true),
                            Forms\Components\TextInput::make('price')
                                ->numeric()
                                ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->decimalPlaces(2)->padFractionalZeros()
                                ),
                        ]),
                ])->columnSpan(['lg' => 1]),
        ];
    }  
}
