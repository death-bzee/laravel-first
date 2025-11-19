<?php

namespace App\Traits\Filament;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;

trait HasFilamentFields
{
    public function getFileUploadComponent(string $collection, $label = false)
    {
        return SpatieMediaLibraryFileUpload::make($collection) // Используем имя коллекции как имя компонента
            ->label($label)
            ->collection($collection)
            ->multiple()
            ->panelLayout('grid gap-2')
            ->previewable(false)
            ->reorderable()
            ->downloadable()
            ->acceptedFileTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'image/png',
                'image/jpeg',
            ])
            ->columnSpanFull();
    }

    public function getRichEditor(string $name, ?string $label = null): RichEditor
    {
        return RichEditor::make($name)
            ->label($label)
            ->columnSpanFull()
            ->toolbarButtons($this->getToolbarButtons());
    }

    public function getToolbarButtons(): array
    {
        return [
            'bold',        // Жирный текст
            'italic',      // Курсив
            'underline',   // Подчеркнутый текст
            'strike',      // Зачеркнутый текст
            'link',        // Вставка ссылки
            'list',        // Списки (общий доступ)
            'orderedList', // Нумерованный список
            'bulletList',  // Маркированный список
            'undo',        // Отмена действия
            'redo',        // Повтор действия
        ];
    }

    public function getTextarea(string $name, $label = false): Textarea
    {
        return Textarea::make($name)
                    ->label(__($label))
                    ->rows(5)
                    ->columnSpanFull();
    }
}
