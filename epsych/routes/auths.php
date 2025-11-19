<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'userIsActive',
    'forcePasswordChange',
    //'ensureTwoFactorEnabled'
    //'verified', email верификация
])->group(function () {

    Route::get('/', function () {
        return redirect()->route('social-passport-school');
    });

    require __DIR__ . '/profile.php';
    require __DIR__ . '/menus.php';
    require __DIR__ . '/bullying.php';
    require __DIR__ . '/surveys.php';
    require __DIR__ . '/students.php';
    require __DIR__ . '/psychologists.php';
    require __DIR__ . '/sociologists.php';
});
