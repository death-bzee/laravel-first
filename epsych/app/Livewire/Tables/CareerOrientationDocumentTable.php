<?php

namespace App\Livewire\Tables;

use App\Contracts\User\UserRoleServiceContract;
use App\Filament\Tables\Columns\FileUploadedColumn;
use App\Models\CareerOrientationDocument;
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

class CareerOrientationDocumentTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentActions;
    use HasFilamentColumns;

    public function table(Table $table): Table
    {
        $organizationIds = app(UserRoleServiceContract::class)->getOrganizationsByUser();

        return $table
            ->query(
                CareerOrientationDocument::query()
                    ->whereIn('organization_id', $organizationIds)
            )
            ->emptyStateHeading(__('Нет записей'))
            ->columns([
                TextColumn::make('title')
                    ->label(__('Название документа'))
                    ->words(8)
                    ->searchable(),

                FileUploadedColumn::make('сareer_orientation_document')
                    ->label(__('Документы'))
                    ->setCollectionName('сareer_orientation_document')
                    ->setTruncateLength(20),

                ...self::getCreationColumns(),
            ])
            ->filters([
                //
            ])
            ->actions([
                $this->editAction()
                    ->url(fn($record) => route('career-orientation-document-edit', $record))
                    ->visible(fn() => auth()->user()->can('update_career::orientation::document'))
                    ->authorize(fn($record) => auth()->user()->can('update_career::orientation::document')),
                $this->deleteAction()
                    ->visible(fn() => auth()->user()->can('delete_career::orientation::document'))
                    ->authorize(fn($record) => auth()->user()->can('delete_career::orientation::document')),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.career-orientation-document-table');
    }
}
