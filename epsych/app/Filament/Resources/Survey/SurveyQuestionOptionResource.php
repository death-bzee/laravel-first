<?php

namespace App\Filament\Resources\Survey;

use App\Filament\Resources\Survey\SurveyQuestionOptionResource\Pages;
use App\Filament\Resources\Survey\SurveyQuestionResource\RelationManagers\OptionsRelationManager;
use App\Models\Survey\SurveyQuestionOption;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SurveyQuestionOptionResource extends Resource
{
    use Translatable;

    protected static ?string $model = SurveyQuestionOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'Ответы';
    protected static ?string $modelLabel = 'Ответ';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_id')
                    ->label('Вопрос')
                    ->relationship('question', 'title')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Ответ')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->rows(10)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('score')
                    ->label('Балл за ответ')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('images')
                    ->label('Изображения')
                    ->disk('public')
                    ->directory('surveys/options/images')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '4:3',
                    ])
                    ->panelLayout('grid')
                    ->multiple()
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->reorderable()
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Ответ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('question.title')
                    ->label('Вопрос')
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->label('Балл за ответ')
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
                SelectFilter::make('survey')
                    ->label('Тест')
                    ->relationship('question.survey', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('question')
                    ->label('Вопрос')
                    ->relationship('question', 'title')
                    ->searchable()
                    ->preload(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyQuestionOptions::route('/'),
            'create' => Pages\CreateSurveyQuestionOption::route('/create'),
            'edit' => Pages\EditSurveyQuestionOption::route('/{record}/edit'),
        ];
    }
}
