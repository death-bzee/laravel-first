<?php

namespace App\Livewire\Tables;

use App\Contracts\User\UserRoleServiceContract;
use App\Filament\Tables\Columns\FileUploadedColumn;
use App\Models\Decree;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DecreeTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentActions;
    use HasFilamentColumns;

    public function table(Table $table): Table
    {
        return $table
            ->query(app(UserRoleServiceContract::class)->applyUserFilterToQuery(Decree::query()))
            ->emptyStateHeading(__('Нет записей'))
            ->columns([
                TextColumn::make('title')
                    ->label(__('Приказ'))
                    ->words(8)
                    ->searchable(),

                FileUploadedColumn::make('decree_form')
                    ->label(__('Файлы'))
                    ->setCollectionName('decree_form')
                    ->setTruncateLength(20),

                ...self::getCreationColumns(),
            ])
            ->filters([
                //
            ])
            ->actions([
                $this->editAction()
                    ->url(fn($record) => route('decree-edit', $record))
                    ->visible(fn() => auth()->user()->can('update_decree'))
                    ->authorize(fn($record) => auth()->user()->can('update_decree')),
                $this->deleteAction()
                    ->visible(fn() => auth()->user()->can('delete_decree'))
                    ->authorize(fn($record) => auth()->user()->can('delete_decree')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.decree-table');
    }
}
