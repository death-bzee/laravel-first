<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\Filament\EsupPanelProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\JetstreamServiceProvider::class,
    Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
];
