<?php

namespace App\Filament\Resources\SocialWorkPlanResource\Pages;

use App\Filament\Resources\SocialWorkPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialWorkPlan extends EditRecord
{
    protected static string $resource = SocialWorkPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
