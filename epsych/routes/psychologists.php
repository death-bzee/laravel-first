<?php

// Work Plans (Просмотр доступен по ролям)
use App\Models\ConsultationJournal;
use App\Models\WorkPlan;

Route::middleware(['role:psychologist|social_pedagogue'])->group(function () {
    Route::get('/work-plans', function () {
        return view('pages.psycholog.work-plan.list');
    })->name('work-plans');

    // Доступ к созданию только с разрешением
    Route::middleware(['can:create_work::plan'])->group(function () {
        Route::get('/work-plans/create', function () {
            return view('pages.psycholog.work-plan.create');
        })->name('work-plan-create');
    });

    // Доступ к редактированию только с разрешением
    Route::middleware(['can:update_work::plan'])->group(function () {
        Route::get('/work-plan/edit/{record}', function (WorkPlan $record) {
            return view('pages.psycholog.work-plan.edit', ['record' => $record]);
        })->name('work-plan-edit');
    });

    // Consultation Journals (Аналогично Work Plans)
    Route::get('/consultation-journals', function () {
        return view('pages.psycholog.consultation-journal.list');
    })->name('consultation-journals');

    Route::middleware(['can:create_consultation::journal'])->group(function () {
        Route::get('/consultation-journal/create', function () {
            return view('pages.psycholog.consultation-journal.create');
        })->name('consultation-journal-create');
    });

    Route::middleware(['can:update_consultation::journal'])->group(function () {
        Route::get('/consultation-journal/edit/{record}', function (ConsultationJournal $record) {
            return view('pages.psycholog.consultation-journal.edit', ['record' => $record]);
        })->name('consultation-journal-edit');
    });

    Route::get('/consultation-journal/view/{record}', function (ConsultationJournal $record) {
        return view('pages.psycholog.consultation-journal.view', ['record' => $record]);
    })->name('consultation-journal-view');
});
