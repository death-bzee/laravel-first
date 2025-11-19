<?php

namespace App\Livewire\Tables;

use App\Models\Organization;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrganizationTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Organization::query()
                    ->where('district_id', auth()->user()->district_id)
                    ->where('is_active', true)
            )
            ->emptyStateHeading(__('Нет записей'))
            ->columns([
                TextColumn::make('bin')
                    ->label(__('БИН организации'))
                    ->searchable(),
                TextColumn::make('title')
                    ->label(__('Организация'))
                    ->searchable()
                    ->limit(80)
                    ->tooltip(fn ($record) => $record->title),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.organization-table');
    }
}
