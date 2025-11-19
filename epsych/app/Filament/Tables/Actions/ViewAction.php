<?php

namespace App\Filament\Tables\Actions;

use Filament\Tables\Actions\Action;

class ViewAction extends Action
{
    protected string $routeName;

    protected string $routeKey = 'id';

    public static function make(?string $name = 'view'): static
    {
        return parent::make($name)
            ->label(__('Просмотр'))
            ->icon('view-primary')
            ->color('primary')
            ->extraAttributes([
                'wire:navigate' => true,
            ]);
    }

    public function route(string $routeName, string $routeKey = 'id'): static
    {
        $this->routeName = $routeName;
        $this->routeKey = $routeKey;

        return $this->url(fn ($record) => route($this->routeName, [
            $this->routeKey => $record->{$this->routeKey},
        ]));
    }
}
