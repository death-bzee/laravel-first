<?php

namespace App\Filament\Resources\Bullying;

use App\Enums\Bullying\BullyingCaseStatusEnum;
use App\Enums\RoleEnum;
use App\Filament\Resources\Bullying\BullyingCaseResource\Filters\IncidentDateFilter;
use App\Filament\Resources\Bullying\BullyingCaseResource\Infolists\BullyingCaseInfoList;
use App\Filament\Resources\Bullying\BullyingCaseResource\Pages;
use App\Filament\Tables\Filters\OrganizationFilter;
use App\Models\Bullying\BullyingCase;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BullyingCaseResource extends Resource
{
    use HasFilamentColumns;

    protected static ?string $model = BullyingCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Зарегистрированные случаи';
    protected static ?string $navigationGroup = 'Буллинг';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('victim')
                    ->required()
                    ->maxLength(255),
                TextInput::make('aggressor')
                    ->maxLength(255)
                    ->default(null),
                DatePicker::make('incident_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('under_review'),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('victim')
                    ->label(__('Потерпевший'))
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('aggressor')
                    ->label(__('Агрессор'))
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('incident_date')
                    ->label(__('Дата инцидента'))
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Статус'))
                    ->badge()
                    ->formatStateUsing(fn(BullyingCaseStatusEnum $state) => $state->getLabel())
                    ->color(fn(BullyingCaseStatusEnum $state) => $state->getColor())
                    ->searchable(),

                TextColumn::make('organization.bin')
                    ->label(__('Организация')),

                ...self::getCreationColumns(),
            ])
            ->filters([
                IncidentDateFilter::make(),
                OrganizationFilter::make()
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('Подробнее'))
                    ->color('primary')
                    ->modalHeading(__('Просмотр случая буллинга'))
                    ->infolist(BullyingCaseInfoList::make()),
                DeleteAction::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListBullyingCases::route('/'),
            'create' => Pages\CreateBullyingCase::route('/create'),
            'edit' => Pages\EditBullyingCase::route('/{record}/edit'),
        ];
    }
}
