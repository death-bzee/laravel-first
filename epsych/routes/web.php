<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    ['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {

        //Livewire Mcamara LaravelLocalization fix
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle);
        });

        //Неавторизованные пользователи
        require __DIR__ . '/guests.php';

        //Fortify and Jetstream routes
        require __DIR__ . '/fortify.php';
        require __DIR__ . '/jetstream.php';

        //Авторизованные верифицированные пользователи
        require __DIR__ . '/auths.php';
    }
);

/*Route::get('/php-info', function() {
    phpinfo();
});*/

/*Route::get('/current-time', function() {
    return [
        'timezone' => date_default_timezone_get(),
        'current_time' => now()->toDateTimeString(),
    ];
});*/
