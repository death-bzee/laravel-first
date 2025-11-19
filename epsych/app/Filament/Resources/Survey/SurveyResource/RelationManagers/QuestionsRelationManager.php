<?php

namespace App\Filament\Resources\Survey\SurveyResource\RelationManagers;

use App\Enums\Survey\SurveyQuestionTypeEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\Concerns\Translatable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    use Translatable;
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Вопросы';

    protected static ?string $modelLabel = 'Вопрос';

    protected static ?string $pluralModelLabel = 'Вопросы';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                Tables\Actions\LocaleSwitcher::make(),
            ]);
    }
}
