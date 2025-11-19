<?php

use App\Models\Bullying\PreventionEvent;

// План профилактики
Route::middleware(['role:psychologist|student_affairs_manager|correctional_service_district|correctional_service_region'])->group(function () {
    Route::get('/bullying/prevention-events', function () {
        return view('pages.bullying.prevention-events.list');
    })->name('bullying-prevention-events');

    // Доступ к созданию только с разрешением
    Route::middleware(['can:create_bullying::prevention::event'])->group(function () {
        Route::get('/bullying/prevention-events/create', function () {
            return view('pages.bullying.prevention-events.create');
        })->name('bullying-prevention-event-create');
    });

    // Доступ к редактированию только с разрешением
    Route::middleware(['can:update_bullying::prevention::event'])->group(function () {
        Route::get('/bullying/prevention-events/edit/{record}', function (PreventionEvent $record) {
            return view('pages.bullying.prevention-events.edit', ['record' => $record]);
        })->name('bullying-prevention-event-edit');
    });
});

// Случаи буллинга
Route::middleware(['role:correctional_service_district|correctional_service_region|student_affairs_manager'])->group(function () {
    Route::get('/bullying/bullying-cases', function () {
        return view('pages.bullying.bullying-cases.list');
    })->name('bullying-bullying-cases');
});
