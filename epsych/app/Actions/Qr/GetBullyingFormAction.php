<?php

namespace App\Actions\Qr;

use App\Contracts\Qr\QrCodeActionContract;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class GetBullyingFormAction implements QrCodeActionContract
{
    public function __construct(protected Component $component)
    {
    }

    public function handle(Model $model): void
    {
        /** @var Organization $model */
        $this->component->reset(['organization']);
        $this->component->organization = $model;

        $this->component->redirect(route('bullying-report', ['organizationId' => $model->id]), navigate: true);
    }
}
