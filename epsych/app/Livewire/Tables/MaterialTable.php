<?php

namespace App\Livewire\Tables;

use App\Filament\Tables\Filters\TextInputFilter;
use App\Models\Material;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class MaterialTable extends Component implements HasForms, HasTable
{
    use HasFilamentActions;
    use HasFilamentColumns;
    use InteractsWithForms;
    use InteractsWithTable;

    public ?string $link = null;

    public ?int $type_id = null;

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        $typeId = (int) $this->type_id;

        return $table
            ->emptyStateHeading(__('Нет записей'))
            ->defaultSort('created_at')
            ->query(
                Material::query()->where('material_type_id', $typeId)
            )
            ->columns([
                TextColumn::make('title')
                    ->label(__('Название'))
					->color('primary')
                    ->url(fn ($record) => url("materials/{$this->link}/{$record->id}")),

                ...self::getCreationColumns(),
            ])
            ->filters([
                TextInputFilter::make('title', __('Название')),
            ])
            ->filtersLayout(FiltersLayout::AboveContent);
    }

    public function render(): View
    {
        return view('livewire.tables.material-table');
    }
}
