<?php

use Illuminate\Support\Facades\Route;

Route::get('/user/logs', function () {
    return view('pages.user.logs');
})->name('profile.logs');
