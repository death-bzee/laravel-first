<?php

namespace App\Filament\Resources\Survey\SurveyQuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\Concerns\Translatable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OptionsRelationManager extends RelationManager
{
    use Translatable;
    protected static string $relationship = 'options';

    protected static ?string $title = 'Ответы';

    protected static ?string $pluralModelLabel = 'Ответы';

    protected static ?string $modelLabel = 'Ответ';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Ответ'),
                Tables\Columns\TextColumn::make('score')
                    ->label('Балл за ответ'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
