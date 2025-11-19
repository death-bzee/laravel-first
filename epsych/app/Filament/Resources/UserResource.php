<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Support\UserActionLogger;
use App\Models\Concerns\District;
use App\Models\User;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = "Пользователь";
    protected static ?string $pluralModelLabel = 'Пользователи';
    protected static ?string $navigationGroup = 'Filament Shield';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('is_active')
                    ->label('Активен')
                    ->required(),
                Grid::make(3)
                    ->schema([
                        TextInput::make('surname')
                            ->label('Фамилия')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('name')
                            ->label('Имя')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('patronymic')
                            ->label('Отчество')
                            ->required()
                            ->columnSpan(1),
                    ]),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state)),

                Grid::make(2)
                    ->schema([
                        Select::make('region_id')
                            ->label('Область')
                            ->relationship(name: 'region', titleAttribute: 'title')
                            ->afterStateUpdated(fn(Set $set) => $set('district_id', null))
                            ->live()
                            ->preload()
                            ->searchable()
                            ->columnSpan(1),

                        Select::make('district_id')
                            ->label('Район')
                            ->options(fn(Get $get): Collection => District::query()
                                ->where('region_id', $get('region_id'))
                                ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->columnSpan(1),
                        Select::make('bin')
                            ->label('БИН организации')
                            ->relationship('organization', 'bin')
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),
                    ]),

                Select::make('classrooms')
                    ->label('Класс')
                    ->relationship('classrooms', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->grade}{$record->letter}")
                    ->preload()
                    ->searchable(),

                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Активен')
                    ->afterStateUpdated(function (User $record, bool $state) {
                        if ($state) {
                            UserActionLogger::logUnblocked($record);
                        } else {
                            UserActionLogger::logBlocked($record);
                        }
                    }),
                TextColumn::make('surname')
                    ->label('Фамилия')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('patronymic')
                    ->label('Отчество')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('organization.bin')
                    ->label('БИН организации')
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('organization_id')
                    ->label(__('БИН организации'))
                    ->relationship('organization', 'bin')
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (User $record) {
                        UserActionLogger::logDeleted($record);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
