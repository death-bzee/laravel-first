<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkPlanResource\Pages;
use App\Models\WorkPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkPlanResource extends Resource
{
    protected static ?string $model = WorkPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'План работ';
    protected static ?string $modelLabel = 'План';
    protected static ?string $navigationGroup = 'Педагоги-психологи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('event_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('activity_direction_id')
                    ->relationship('activityDirection', 'title')
                    ->default(null),
                Forms\Components\Select::make('target_group_id')
                    ->relationship('targetGroup', 'title')
                    ->default(null),
                Forms\Components\DatePicker::make('execution_deadline')
                    ->required(),
                Forms\Components\Textarea::make('completion_form')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('responsible_person')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('execution_status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activityDirection.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('targetGroup.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('execution_deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('execution_status')
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
            'index' => Pages\ListWorkPlans::route('/'),
            'create' => Pages\CreateWorkPlan::route('/create'),
            'edit' => Pages\EditWorkPlan::route('/{record}/edit'),
        ];
    }
}
