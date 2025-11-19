<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;

class FileUploadedColumn extends Column
{
	protected string $view = 'filament.tables.columns.file-uploaded-column';
	protected string $collectionName = 'default';
	protected ?int $truncateLength = null;

	public function setCollectionName(string $name): static
	{
		$this->collectionName = $name;
		return $this;
	}

	public function setTruncateLength(?int $length): static
	{
		$this->truncateLength = is_int($length) ? $length : null;
		return $this;
	}

	public function getFiles(): array
	{
		$record = $this->getRecord();
		return $record?->getMedia($this->collectionName)->all() ?? [];
	}

	public function getTruncateLength(): ?int
	{
		return $this->truncateLength;
	}
}
