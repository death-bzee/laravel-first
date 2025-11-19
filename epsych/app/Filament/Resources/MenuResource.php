<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    use Translatable;

    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Меню';
    protected static ?string $navigationGroup = 'Контент';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make('Контент')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Заголовок')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                TextInput::make('icon')
                                    ->label('CSS Класс иконки')
                                    ->columnSpanFull(),
                                Select::make('parent_id')
                                    ->options(Menu::all()->pluck('title', 'id'))
                                    ->default(null)
                                    ->label('Родительский пункт меню'),
                                TextInput::make('link')
                                    ->label('Ссылка')
                                    ->required()
                                    ->columnSpanFull(),
                                Select::make('roles')
                                    ->label('Доступные роли')
                                    ->options(Role::all()->pluck('name', 'name')) // Получаем роли
                                    ->multiple() // Разрешаем мультивыбор
                                    ->preload() // Подгружает список сразу
                                    ->searchable() // Разрешает поиск
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(8),

                        Section::make('Настройки')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Активен')
                                    ->default(true),
                                Toggle::make('_blank')
                                    ->label('Открывать в новой вкладке')
                                    ->default(false),
                            ])
                            ->grow(false)
                            ->columnSpan(4),
                    ])
                    ->columns(12)
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ToggleColumn::make('is_active')
                    ->label('Активен'),
                ToggleColumn::make('_blank')
                    ->label('В новой вкладке'),
                TextColumn::make('title')
                    ->label('Заголовок'),
                TextColumn::make('link')
                    ->label('Ссылка')
                    ->copyable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                // ...
                Tables\Actions\LocaleSwitcher::make(),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
