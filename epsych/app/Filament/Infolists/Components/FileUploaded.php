<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;

class FileUploaded extends Entry
{
    protected string $view = 'filament.infolists.components.file-uploaded';

    protected string $collectionName = 'default'; // Коллекция по умолчанию

    public function setCollectionName(string $name): static
    {
        $this->collectionName = $name;
        return $this;
    }

    public function getFiles(): array
    {
        $record = $this->getRecord(); // Получаем запись
        return $record?->getMedia($this->collectionName)->all() ?? []; // Преобразуем MediaCollection в массив
    }
}
