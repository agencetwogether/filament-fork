<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MealResource\Pages;
use App\Models\Meal;
use App\Models\Dish;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;

use Camya\Filament\Forms\Components\TitleWithSlugInput;

class MealResource extends Resource
{
    protected static ?string $model = Meal::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                TitleWithSlugInput::make(
                                    fieldTitle: 'name',
                                    titleLabel: 'Name',
                                )
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                        ->numeric()
                                        ->decimalPlaces(2)->padFractionalZeros()
                                    )
                                    ->columnSpan(1),
                                Forms\Components\RichEditor::make('description')
                                    ->disableAllToolbarButtons()
                                    ->enableToolbarButtons([ 'bold', 'italic', 'strike' ])
                                    ->columnSpan('full'),
                                Forms\Components\RichEditor::make('notes')
                                    ->disableAllToolbarButtons()
                                    ->enableToolbarButtons([ 'bold', 'italic', 'strike' ])
                                    ->columnSpan('full'),
                            ])->columns(4),

                        Forms\Components\Section::make('content items')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->columns(2)
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('category_id')
                                            ->options(Category::pluck('name', 'id')->toArray())
                                            ->reactive()
                                            ->afterStateUpdated( fn (callable $set) => $set('dish_id', null))
                                            ->columnSpan(1),

                                        Forms\Components\Select::make('dish_id')
                                            ->options(function ( callable $get ){
                                                $category = Category::find( $get('category_id') );

                                                if( !$category ){
                                                    return Dish::pluck('name', 'id');
                                                }

                                                return $category->dishes->pluck('name', 'id');
                                            })
                                            ->required()
                                            ->lazy()
                                            ->afterStateHydrated(fn ($state, callable $set) => $set('category_id', Dish::find($state)?->category->id ?? 0))
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('category_id', Dish::find($state)?->category->id ?? 0))
                                            ->columnSpan(1)
                                            ->createOptionForm(DishResource::getFormGlobalSchema())
                                            ->createOptionUsing(fn ($data) => Dish::create($data)->getKey()),

                                        Forms\Components\Select::make('separator')
                                            ->options([
                                                'or' => 'or',
                                                'and' => 'and'
                                            ])
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan('full'),
                                        Forms\Components\TextInput::make('extra_price')
                                            ->numeric()
                                            ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                                ->numeric()
                                                ->decimalPlaces(2)->padFractionalZeros()
                                            )
                                            ->columnSpan('full'),
                                    ])
                                    ->orderable()
                                    ->collapsible()
                                    ->required()
                                    ->defaultItems(1)
                                    ->disableLabel(),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('status')
                            ->schema([
                                Forms\Components\DatePicker::make('published_at')
                            ]),

                        Forms\Components\Section::make('image')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection('meals-images')
                                    ->image()
                                    ->disableLabel(),
                            ]),
                    ])->columnSpan(['lg' => 1]),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('meals-images')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListMeals::route('/'),
            'create' => Pages\CreateMeal::route('/create'),
            'edit' => Pages\EditMeal::route('/{record}/edit'),
        ];
    }    
}
