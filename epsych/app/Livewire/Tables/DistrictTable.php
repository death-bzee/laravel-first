<?php

namespace App\Livewire\Tables;

use App\Models\Concerns\District;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DistrictTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(District::query()->where('region_id', auth()->user()->region_id))
            ->emptyStateHeading(__('Нет записей'))
            ->columns([
                TextColumn::make('districtCode')
                    ->label(__('Код района'))
                    ->searchable(),
                TextColumn::make('title')
                    ->label(__('Район'))
                    ->searchable(),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.district-table');
    }
}
