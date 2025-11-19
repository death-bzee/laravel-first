<?php

namespace App\Filament\Resources\Survey;

use App\Filament\Resources\Survey\SurveyAssignmentResource\Pages;
use App\Models\Student;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyGroupAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class SurveyAssignmentResource extends Resource
{
    protected static ?string $model = SurveyAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?int $navigationSort = 5;
    protected static ?string $pluralModelLabel = 'Связи';
    protected static ?string $modelLabel = 'Связь';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('group_id')
                    ->label('Группа')
                    ->relationship('group', 'title')
                    ->preload()
                    ->live()
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(fn (callable $set) => $set('student_id', null)),

                Forms\Components\Select::make('student_id')
                    ->label('ФИО школьника')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->options(function (Get $get): Collection {
                        // Получаем выбранный group_id
                        $groupId = $get('group_id');

                        // Получаем соответствующий SurveyGroupAssignment
                        $groupAssignment = SurveyGroupAssignment::find($groupId);

                        // Если связь найдена, фильтруем студентов
                        if ($groupAssignment) {
                            return Student::query()
                                ->where('organization_id', $groupAssignment->organization_id)
                                ->where('classroom_id', $groupAssignment->classroom_id)
                                ->get()
                                ->mapWithKeys(fn($model) => [
                                    $model->id => "{$model->surname} {$model->name} {$model->patronymic}"
                                ]);
                        }

                        // Если связь не найдена, возвращаем пустую коллекцию
                        return collect();
                    })
                    ->columnSpanFull()
                    ->visible(fn($get) => !is_null($get('group_id'))),

                Forms\Components\DateTimePicker::make('assigned_at')
                    ->label('Дата и время назначения теста'),
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
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('group.title')
                    ->label('Группа')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.id')
                    ->label('ИД студента')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('ФИО студента')
                    ->formatStateUsing(function ($record) {
                        return "{$record->student->surname} {$record->student->name} {$record->student->patronymic}";
                    }),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Дата назначения')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Дата cтарта')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Дата завершения')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListSurveyAssignments::route('/'),
            'create' => Pages\CreateSurveyAssignment::route('/create'),
            'edit' => Pages\EditSurveyAssignment::route('/{record}/edit'),
        ];
    }
}
