<?php

namespace App\Filament\Resources\Survey;

use App\Filament\Resources\Survey\SurveyResource\Pages;
use App\Filament\Resources\Survey\SurveyResource\RelationManagers\QuestionsRelationManager;
use App\Models\Survey\Survey;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class SurveyResource extends Resource
{
    use Translatable;

    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Тесты';
    protected static ?string $modelLabel = 'Тест';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Описание')
                    ->rows(10)
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->label('Изображения')
                    ->disk('public')
                    ->directory('surveys/images')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '4:3',
                    ])
                    ->panelLayout('grid')
                    ->multiple()
                    ->reorderable()
                    ->columnSpanFull(),

                Toggle::make('has_level_group')
                    ->label('Связать с группой уровней')
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        if (!$state) {
                            $set('level_group_id', null); // Если выключаем Toggle, сбрасываем выбор группы
                        }
                    })
                    ->columnSpanFull(),

                Select::make('level_group_id')
                    ->label('Группа')
                    ->relationship('levelGroup', 'title')
                    ->preload()
                    ->searchable()
                    ->reactive()
                    ->hidden(fn($get) => !$get('has_level_group')) // Скрываем, если Toggle выключен
                    ->afterStateUpdated(function ($set, $state) {
                        if ($state) {
                            $set('has_level_group', true); // Включаем Toggle, если выбрана группа
                        } else {
                            $set('has_level_group', false); // Выключаем Toggle, если поле очищено
                        }
                    })
                    ->columnSpanFull(),

                JsonColumn::make('interpretation')
                    ->label('Интерпретация результатов в JSON')
                    ->editorHeight(500)
					->viewerHeight(500)
                    ->columnSpanFull(),
                JsonColumn::make('scaling_prompt')
                    ->label('Промт для шкалирования')
                    ->editorHeight(500)
					->viewerHeight(500)
                    ->columnSpanFull(),
                Textarea::make('interpretation_prompt')
                    ->label('Промт для интерпретации')
                    ->rows(10)
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable()
            ->columns([
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                Tables\Actions\LocaleSwitcher::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
