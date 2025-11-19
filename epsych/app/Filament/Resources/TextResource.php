<?php

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\TextResource\Pages;
use App\Models\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TextResource extends Resource
{
    use Translatable;

    protected static ?string $model = Text::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'Текст на сайте';
    protected static ?string $modelLabel = 'Текст';
    protected static ?string $navigationGroup = 'Контент';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('active')
					->label('Активен')
                    ->default(true)
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
					->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255)
                    ->columnSpanFull(),
				TinyEditor::make('text')
                    ->label('Текст')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('text/tynyeditor/files')
                    ->profile('full')
                    ->columnSpan('full')
                    ->maxHeight(500)
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->label('Ссылка на эту страницу')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
			->defaultSort('sort')
			->reorderable('sort')
            ->columns([
				Tables\Columns\ToggleColumn::make('active')
					->label('Активен'),
				Tables\Columns\TextColumn::make('title')
					->label('Заголовок')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
					->label('Ссылка')
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                // ...
                Tables\Actions\LocaleSwitcher::make(),
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
            'index' => Pages\ListTexts::route('/'),
            'create' => Pages\CreateText::route('/create'),
            'edit' => Pages\EditText::route('/{record}/edit'),
        ];
    }
}
