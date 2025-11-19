<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialWorkPlanResource\Pages;
use App\Models\SocialWorkPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialWorkPlanResource extends Resource
{
    protected static ?string $model = SocialWorkPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'План работ';
    protected static ?string $modelLabel = 'План';
    protected static ?string $navigationGroup = 'Педагоги-социологи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('event_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('execution_deadline')
                    ->required(),
                Forms\Components\TextInput::make('type_responsible_person')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('type_form_report')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('execution_deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_responsible_person')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type_form_report')
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
            'index' => Pages\ListSocialWorkPlans::route('/'),
            'create' => Pages\CreateSocialWorkPlan::route('/create'),
            'edit' => Pages\EditSocialWorkPlan::route('/{record}/edit'),
        ];
    }
}
