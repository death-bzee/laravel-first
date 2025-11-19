<?php

namespace App\Filament\Resources\Survey;

use App\Filament\Resources\Survey\SurveyResultResource\Pages;
use App\Models\Student;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionOption;
use App\Models\Survey\SurveyResult;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SurveyResultResource extends Resource
{
    protected static ?string $model = SurveyResult::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?int $navigationSort = 5;
    protected static ?string $pluralModelLabel = 'Результаты';
    protected static ?string $modelLabel = 'Результат';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_assignment_id')
                    ->label('ИД связи')
                    ->relationship('surveyAssignment', 'id')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->afterStateUpdated(fn (callable $set) => $set('survey_id', null)),
                Forms\Components\Select::make('survey_id')
                    ->label('Тест')
                    ->relationship('survey', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->options(fn(Get $get): Collection => Survey::query()
                        ->whereHas('assignments', function (Builder $query) use ($get) {
                            $query->where('id', $get('survey_assignment_id'));
                        })
                        ->pluck('title', 'id')
                    ),
                Forms\Components\Select::make('question_id')
                    ->label('Вопрос')
                    ->relationship('question', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->options(fn(Get $get): Collection => SurveyQuestion::query()
                        ->where('survey_id', $get('survey_id'))
                        ->pluck('title', 'id')
                    ),
                Forms\Components\Select::make('option_id')
                    ->label('Ответ')
                    ->relationship('option', 'title')
                    ->searchable()
                    ->preload()
                    ->default(null)
                    ->options(fn(Get $get): Collection => SurveyQuestionOption::query()
                        ->where('question_id', $get('question_id'))
                        ->pluck('title', 'id')
                    ),
                Forms\Components\Select::make('student_id')
                    ->label('Школьник')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(fn(Get $get): Collection => Student::query()
                        ->whereHas('assignments', function (Builder $query) use ($get) {
                            $query->where('id', $get('survey_assignment_id'));
                        })
                        ->orderBy('surname')
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn ($student) => [
                            $student->id => "{$student->surname} {$student->name} {$student->patronymic}"
                        ])
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('surveyAssignment.id')
                    ->label('ИД связи')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('surveyAssignment.group.survey.title')
                    ->label('Тест')
                    ->numeric()
                    ->words(10)
                    ->sortable(),
                Tables\Columns\TextColumn::make('question.title')
                    ->label('Вопрос')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('option.title')
                    ->label('Ответ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('ФИО студента')
                    ->formatStateUsing(function ($record) {
                        return "{$record->student->surname} {$record->student->name} {$record->student->patronymic}";
                    }),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSurveyResults::route('/'),
            'create' => Pages\CreateSurveyResult::route('/create'),
            'edit' => Pages\EditSurveyResult::route('/{record}/edit'),
        ];
    }
}
