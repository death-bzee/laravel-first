<?php

namespace App\Filament\Resources\SocialWorkPlanResource\Pages;

use App\Filament\Resources\SocialWorkPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialWorkPlans extends ListRecords
{
    protected static string $resource = SocialWorkPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
