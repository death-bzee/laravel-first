<?php

use Illuminate\Support\Facades\Route;

//Survey Group Assignment
Route::get('/survey-group-assign', function () {
    return view('pages.survey-group-assign.list');
})->name('survey-group-assign');
Route::get('/survey-group-assign/create', function () {
    return view('pages.survey-group-assign.create');
})->name('survey-group-assign-create');

// ($id приводится к числовому значению)
Route::get('/survey-group-assign/edit/{id}', function ($id) {
    return view('pages.survey-group-assign.edit', ['id' => $id]);
})->name('survey-group-assign-edit');

Route::get('/survey-group-assign/codes/{id}', function ($id) {
    return view('pages.survey-group-assign.codes', ['id' => $id]);
})->name('survey-group-assign-codes');
Route::get('/survey-group-assign/view/{id}', function ($id) {
    return view('pages.survey-group-assign.view', ['id' => $id]);
})->name('survey-group-assign-view');

//Survey Assignment
Route::get('/survey-assign', function () {
    return view('pages.survey-assign.list');
})->name('survey-assign');
Route::get('/survey-assign/create', function () {
    return view('pages.survey-assign.create');
})->name('survey-assign-create');

//Results ($id приведен к числовому значению)
Route::get('/results/{id}', function ($id) {
    return view('pages.results.view', ['id' => $id]);
})->name('results.view');
