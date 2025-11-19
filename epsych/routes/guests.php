<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Survey\LoginShortCode;

// Токен получаем из адресной строки
Route::middleware(['isToken'])->group(function () {
    Route::get('/access/dashboard/{token?}', function ($token = null) {
        return view('pages.dashboard.view-guest', ['token' => $token]);
    })->name('guest-dashboard');

    Route::get('/access/events/{token?}', function ($token = null) {
        return view('pages.events.list-guest', ['token' => $token]);
    })->name('guest-events');
});

// Форма авторизации для получения токена
Route::middleware(['redirectIfNotToken'])->group(function () {
    Route::get('/survey', function () {
        return view('pages.survey.view');
    })->name('survey');
});

Route::get('/student/login', function () {
    return view('pages.students.login');
})->name('student-login');

Route::get('/text/{slug}', function (string $slug) {
    return view('pages.texts.view', compact('slug'));
})->name('text');

Route::get('/qr-scanner/', function () {
    return view('pages.survey.qr-scanner');
})->name('qr-scanner');

Route::get('/student/login-short-code', LoginShortCode::class)
    ->name('student.login-short-code');

Route::get('/student/login-qr-code', function () {
    return view('pages.students.login-qr-code');
})->name('student.login-qr-code');

// Форма регистрации случая буллинга
Route::get('/bullying/report/{organizationId}', function ($organizationId) {
    return view('pages.bullying.bullying-cases.report', [
        'organizationId' => $organizationId,
    ]);
})->name('bullying-report');
Route::get('/bullying/report/{organizationId}/sent', function () {
    return view('pages.bullying.bullying-cases.report-sent');
})->name('bullying-report-sent');
