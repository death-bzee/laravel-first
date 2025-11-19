<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistrictResource\Pages;
use App\Models\Concerns\District;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DistrictResource extends Resource
{
    protected static ?string $model = District::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

    protected static ?string $pluralModelLabel = 'Районы';

    protected static ?string $modelLabel = 'Район';

    protected static ?string $navigationGroup = 'Настройки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('districtCode')
                    ->label(__('Код района'))
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('title')
                    ->label(__('Название района'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('region_id')
                    ->label(__('Регион'))
                    ->relationship('region', 'title')
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->label(__('Номер телефона'))
                    ->tel()
					->mask('+7 999 999-99-99')
					->placeholder('+7 777 123-45-67')
                    ->maxLength(20)
                    ->nullable(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('districtCode')
                    ->label(__('Код района'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('Название района'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('region.title')
                    ->label(__('Регион'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Телефон'))
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region_id')
                    ->label(__('Регион'))
                    ->relationship('region', 'title'),

                Tables\Filters\Filter::make('has_phone')
                    ->label(__('С телефоном'))
                    ->query(fn ($query) => $query->whereNotNull('phone')),
				Tables\Filters\Filter::make('organization_bin')
					->label(__('По БИН организации'))
					->form([
						Forms\Components\TextInput::make('bin')
							->label(__('БИН'))
							->placeholder(__('Введите БИН организации')),
					])
					->query(function ($query, array $data) {
						return $query->when(
							$data['bin'] ?? null,
							fn ($q, $bin) => $q->whereHas('organizations', fn ($q2) => $q2->where('bin', 'like', "%{$bin}%"))
						);
					}),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDistricts::route('/'),
            'create' => Pages\CreateDistrict::route('/create'),
            'edit' => Pages\EditDistrict::route('/{record}/edit'),
        ];
    }
}
