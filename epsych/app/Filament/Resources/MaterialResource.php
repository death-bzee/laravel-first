<?php

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MaterialResource extends Resource
{
    use Translatable;

    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Ресурсные материалы';
    protected static ?string $modelLabel = 'Ресурсный материал';
    protected static ?string $navigationGroup = 'Контент';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('material_type_id')
                    ->label('Типы материалов')
                    ->relationship('materialType', 'title')
                    ->required(),
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->columnSpanFull(),
                /*RichEditor::make('text')
                    ->label('Текст')
                    ->columnSpanFull(),*/
                TinyEditor::make('text')
                    ->label('Текст')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('material/tynyeditor/files')
                    ->profile('full')
                    ->columnSpan('full')
                    ->maxHeight(500)
                    ->required(),
                Section::make('Прикрепляемые медиа материалы')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Изображения')
                            ->disk('public')
                            ->directory('material/images')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '4:3',
                            ])
                            ->imageCropAspectRatio('4:3')
                            ->panelLayout('grid')
                            ->multiple()
                            ->reorderable()
                            ->columnSpanFull(),
                        Repeater::make('videos')
                            ->label('Видео')
                            ->schema([
                                TextInput::make('link')
                                    ->label('Ссылка на видео YouTube'),
                            ])
                            ->addActionLabel('Добавить еще ссылку')
                            ->columnSpanFull(),
                        FileUpload::make('files')
                            ->label('Прикрепленные файлы')
                            ->disk('public')
                            ->directory('material/files')
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => (string)str($file->getClientOriginalName())
                                    ->append('-' . uniqid()),
                            )
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ])
                            ->reorderable()
                            ->multiple()
                            ->columnSpanFull(),
                    ]),

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
                Tables\Columns\TextColumn::make('id')
                    ->label('ИД')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('materialType.title')
                    ->label('Тип материала')
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
                SelectFilter::make('material_type_id')
                    ->label('Тип материалов')
                    ->relationship('materialType', 'title')
                    ->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
