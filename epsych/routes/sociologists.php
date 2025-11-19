<?php

use App\Models\SocialWorkPlan;

// Social Work Plans (Просмотр доступен по ролям, создание/редактирование по `can`)
Route::middleware(['role:social_pedagogue|psychologist'])->group(function () {
    Route::get('/social-work-plans', function () {
        return view('pages.social-pedagogue.work-plan.list');
    })->name('social-work-plans');

    Route::middleware(['can:create_work::plan'])->group(function () {
        Route::get('/social-work-plans/create', function () {
            return view('pages.social-pedagogue.work-plan.create');
        })->name('social-work-plan-create');
    });

    Route::middleware(['can:update_work::plan'])->group(function () {
        Route::get('/social-work-plan/edit/{record}', function (SocialWorkPlan $record) {
            return view('pages.social-pedagogue.work-plan.edit', ['record' => $record]);
        })->name('social-work-plan-edit');
    });
});
