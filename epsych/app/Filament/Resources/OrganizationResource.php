<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Concerns\District;
use App\Models\Organization;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class OrganizationResource extends Resource
{
    use Translatable;

    protected static ?string $model = Organization::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Организации';
    protected static ?string $modelLabel = 'Организация';
    protected static ?string $navigationGroup = 'Настройки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
				Select::make('region_id')
					->label('Область')
					->relationship(name: 'region', titleAttribute: 'title')
					->afterStateUpdated(fn(Set $set) => $set('district_id', null))
					->live()
					->preload()
					->searchable()
					->required(),
				Select::make('district_id')
					->label('Район')
					->options(fn(Get $get): Collection => District::query()
						->where('region_id', $get('region_id'))
						->pluck('title','id')
					)
					->searchable()
					->preload()
					->required(),
                TextInput::make('title')
					->label('Название организации')
                    ->required(),
				Grid::make()
					->schema([
						TextInput::make('address')
							->label('Адрес')
							->required(),
						TextInput::make('bin')
							->label('БИН')
							->required()
							->maxLength(12),
					])->columns(2),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
				ToggleColumn::make('is_active')
					->label('Активен'),
                TextColumn::make('title')
					->label('Название организации')
					->limit(80)
                    ->searchable(),
				TextColumn::make('bin')
					->label('БИН')
                    ->searchable(),
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
