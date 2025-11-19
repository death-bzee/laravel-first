<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Actions\ImportStudentsAction;
use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $pluralModelLabel = 'Ученики';

    protected static ?string $modelLabel = 'Ученик';

    protected static ?string $navigationGroup = 'Настройки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('photo')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('surname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('patronymic')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('general_characteristics')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('iin')
                    ->required()
                    ->maxLength(12),
                Forms\Components\DatePicker::make('birthday')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('special_marks')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('incidents')
                    ->columnSpanFull(),
                Forms\Components\Select::make('language_id')
                    ->relationship('language', 'title')
                    ->default(null),
                Forms\Components\Select::make('organization_id')
                    ->relationship('organization', 'bin')
                    ->default(null),
                Forms\Components\Select::make('classroom_id')
                    ->relationship('classroom', 'id')
                    ->default(null),
                // Forms\Components\Select::make('specialStatuses')
                //     ->label(__('Особые статусы'))
                //     ->relationship('specialStatuses', 'title')
                //     ->multiple()
                //     ->preload()
                //     ->searchable()
                //     ->placeholder(__('Выберите статусы')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('photo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patronymic')
                    ->searchable(),
                Tables\Columns\TextColumn::make('iin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('organization.bin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('classroom.id')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ImportStudentsAction::make(),

                Action::make('template')
                    ->label(__('Скачать шаблон'))
                    ->icon('icon-file-excel')
                    ->color('success')
                    ->url(asset('templates/student-import.xlsx')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
