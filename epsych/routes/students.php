<?php

use App\Models\Student;

// Students (Просмотр списка доступен только с разрешением)
Route::middleware(['can:view_any_student'])->group(function () {
    Route::get('/students', function () {
        return view('pages.students.list');
    })->name('students');
});

// Доступ к созданию только с разрешением
Route::middleware(['can:create_student'])->group(function () {
    Route::get('/students/create', function () {
        return view('pages.students.create');
    })->name('student-create');
});

// Доступ к просмотру отдельного студента только с разрешением
Route::get('/students/view/{record}', function (Student $record) {
    return view('pages.students.view', ['record' => $record]);
})->name('student-view');

// Доступ к редактированию только с разрешением
Route::middleware(['can:update_student'])->group(function () {
    Route::get('/students/edit/{record}', function (Student $record) {
        return view('pages.students.edit', ['record' => $record]);
    })->name('student-edit');
});
