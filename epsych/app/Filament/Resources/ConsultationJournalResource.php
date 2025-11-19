<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultationJournalResource\Pages;
use App\Models\ConsultationJournal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ConsultationJournalResource extends Resource
{
    protected static ?string $model = ConsultationJournal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Журнал учета';
    protected static ?string $modelLabel = 'Запись учета';
    protected static ?string $navigationGroup = 'Педагоги-психологи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Дата')
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->options(function () {
                        return \App\Models\Student::query()
                            ->get()
                            ->mapWithKeys(fn($user) => [
                                $user->id => $user->fullName,
                            ]);
                    })
                    ->preload()
                    ->searchable()
                    ->default(null),
                Forms\Components\Textarea::make('request')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('recommendations')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('consultant')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultant')
                    ->searchable(),
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
            'index' => Pages\ListConsultationJournals::route('/'),
            'create' => Pages\CreateConsultationJournal::route('/create'),
            'edit' => Pages\EditConsultationJournal::route('/{record}/edit'),
        ];
    }
}
