<?php

namespace App\Filament\Resources\Survey;

use App\Enums\Survey\SurveyQuestionTypeEnum;
use App\Filament\Resources\Survey\SurveyQuestionResource\Pages;
use App\Filament\Resources\Survey\SurveyQuestionResource\RelationManagers\OptionsRelationManager;
use App\Models\Survey\SurveyQuestion;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SurveyQuestionResource extends Resource
{
    use Translatable;

    protected static ?string $model = SurveyQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Вопросы';
    protected static ?string $modelLabel = 'Вопрос';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_id')
                    ->label('Тест')
                    ->preload()
                    ->relationship('survey', 'title')
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->label('Номер вопроса')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->rows(10)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('images')
                    ->label('Изображения')
                    ->disk('public')
                    ->directory('surveys/questions/images')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '4:3',
                    ])
                    ->panelLayout('grid')
                    ->multiple()
                    ->reorderable()
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label('Тип теста')
                    ->live()
                    ->options(SurveyQuestionTypeEnum::options())
                    ->required(),
                Forms\Components\TextInput::make('limited_multiple_choice')
                    ->label('Максимальное кол-во ответов')
                    ->numeric()
                    ->default(null)
                    ->visible(fn ($get) => $get('type') === SurveyQuestionTypeEnum::LimitedMultipleChoice->value)
                    ->required(fn ($get) => $get('type') === SurveyQuestionTypeEnum::LimitedMultipleChoice->value),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('number')
            ->defaultSort('number')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->label('Номер вопроса')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Вопрос')
                    ->sortable(),
                Tables\Columns\TextColumn::make('survey.title')
                    ->label('Тест')
                    ->numeric()
                    ->words(5)
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
                SelectFilter::make('survey')
                    ->label('Тест')
                    ->relationship('survey', 'title')
                    ->searchable()
                    ->preload()
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
            OptionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyQuestions::route('/'),
            'create' => Pages\CreateSurveyQuestion::route('/create'),
            'edit' => Pages\EditSurveyQuestion::route('/{record}/edit'),
        ];
    }
}
