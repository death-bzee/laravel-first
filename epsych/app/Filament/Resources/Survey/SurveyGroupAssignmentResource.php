<?php

namespace App\Filament\Resources\Survey;

use App\Enums\Survey\SurveyGroupAssignmentTypeEnum;
use App\Enums\Survey\SurveyGroupAssignmentStatusEnum;
use App\Filament\Resources\Survey\SurveyGroupAssignmentResource\Pages;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Survey\SurveyGroupAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SurveyGroupAssignmentResource extends Resource
{
    protected static ?string $model = SurveyGroupAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?int $navigationSort = 4;
    protected static ?string $pluralModelLabel = 'Группы';
    protected static ?string $modelLabel = 'Группа';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Тип связи с тестом')
                    ->options(SurveyGroupAssignmentTypeEnum::class::options())
                    ->required(),
                Forms\Components\Select::make('survey_id')
                    ->label('Тест')
                    ->relationship('survey', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('organization_id')
                    ->label('Организация')
                    ->relationship('organization', 'bin')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(fn(callable $set) => $set('student_id', null)),
                Forms\Components\Select::make('classroom_id')
                    ->label('Класс')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull()
                    ->options(fn(Get $get): Collection => Classroom::query()
                        ->whereIn('id', Student::query()
                            ->whereHas('organization', function (Builder $query) use ($get) {
                                $query->where('id', $get('organization_id'));
                            })
                            ->pluck('classroom_id')
                        )
                        ->get()
                        ->mapWithKeys(fn($model) => [$model->id => "{$model->grade} {$model->letter}"])
                    )
                    ->visible(fn($get) => !is_null($get('organization_id'))),

                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options(SurveyGroupAssignmentStatusEnum::class)
                    ->required(),
                Forms\Components\DateTimePicker::make('assigned_at')
                    ->label('Дата и время назначения теста')
                    ->default(now()),
                Forms\Components\DateTimePicker::make('started_at')
                    ->label('Дата и время старта теста'),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->label('Дата и время завершения теста'),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organization.bin')
                    ->label('Организация')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classroom.grade')
                    ->label('Класс')
                    ->formatStateUsing(function ($record) {
                        return "{$record->classroom->grade}{$record->classroom->letter}";
                    }),
                Tables\Columns\TextColumn::make('survey.title')
                    ->label('Тест')
                    ->words(5)
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Тип назначения')
                    ->formatStateUsing(function ($state) {
                        // Проверяем, является ли $state экземпляром SurveyAssignmentTypeEnum
                        if ($state instanceof SurveyGroupAssignmentTypeEnum) {
                            return $state->getLabel();
                        }

                        // Если $state это строка, конвертируем её в enum
                        return SurveyGroupAssignmentTypeEnum::from($state)->getLabel();
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
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->filters([
                SelectFilter::make('organization_id')
                    ->label('Организация')
                    ->relationship('organization', 'bin')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('survey')
                    ->label('Тест')
                    ->relationship('survey', 'title')
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
            'index' => Pages\ListSurveyGroupAssignments::route('/'),
            'create' => Pages\CreateSurveyGroupAssignment::route('/create'),
            'edit' => Pages\EditSurveyGroupAssignment::route('/{record}/edit'),
        ];
    }
}
