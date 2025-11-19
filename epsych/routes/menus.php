<?php

use App\Models\CareerOrientationDocument;
use App\Models\Decree;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('pages.dashboard.view');
})->name('dashboard');

// Scaling Diagram
Route::get('/scaling-diagram', function () {
    return view('pages.diagrams.view');
})->name('scaling-diagram');


//Районы
Route::middleware(['role:correctional_service_region|correctional_service_district'])->group(function () {
    // Survey Report Table
    Route::get('/survey-report-table', function () {
        return view('pages.diagrams.table');
    })->name('survey-report-table');
});


// Classrooms
Route::get('/classrooms', function () {
    return view('pages.classrooms.list');
})->name('classrooms');

//($id приведен к числовому значению)
Route::get('/classrooms/view/{id}', function ($id) {
    return view('pages.classrooms.view', ['id' => $id]);
})->name('classroom-view');

Route::get('/survey-statistics/{id}', function ($id) {
    return view('pages.survey.statistics', ['id' => $id]);
})->name('survey.statistics');

//Social Passport View
Route::get('/social-passport-school', function () {
    return view('pages.organization.view');
})->name('social-passport-school');

//Materials
Route::get('/materials/articles', function () {
    return view('pages.materials.articles', ['link' => 'articles']);
})->name('articles');
Route::get('/materials/videos', function () {
    return view('pages.materials.videos', ['link' => 'videos']);
})->name('videos');
Route::get('/materials/manuals', function () {
    return view('pages.materials.manuals', ['link' => 'manuals']);
})->name('manuals');
//($id приведен к числовому значению, $link валидируется от xss и sql инъекций)
Route::get('/materials/{link}/{id}', function ($link, $id) {
    return view('pages.materials.view', ['link' => $link, 'id' => $id]);
})->name('materials.view');

//Приказы
Route::middleware(['role:psychologist|social_pedagogue|student_affairs_manager'])->group(function () {
    Route::get('/decrees', function () {
        return view('pages.decrees.list');
    })->name('decrees');

    // Доступ к созданию только с разрешением
    Route::middleware(['can:create_decree'])->group(function () {
        Route::get('/decrees/create', function () {
            return view('pages.decrees.create');
        })->name('decree-create');
    });

    // Доступ к редактированию только с разрешением
    Route::middleware(['can:update_decree'])->group(function () {
        Route::get('/decrees/edit/{record}', function (Decree $record) {
            return view('pages.decrees.edit', ['record' => $record]);
        })->name('decree-edit');
    });
});

//Профориентация
Route::middleware(['role:psychologist|social_pedagogue|student_affairs_manager'])->group(function () {
    Route::get('/career-orientation-documents', function () {
        return view('pages.career-orientation-documents.list');
    })->name('career-orientation-documents');

    // Доступ к созданию только с разрешением
    Route::middleware(['can:create_career::orientation::document'])->group(function () {
        Route::get('/career-orientation-documents/create', function () {
            return view('pages.career-orientation-documents.create');
        })->name('career-orientation-document-create');
    });

    // Доступ к редактированию только с разрешением
    Route::middleware(['can:update_career::orientation::document'])->group(function () {
        Route::get('/career-orientation-documents/edit/{record}', function (CareerOrientationDocument $record) {
            return view('pages.career-orientation-documents.edit', ['record' => $record]);
        })->name('career-orientation-document-edit');
    });
});

//Районы
Route::middleware(['role:correctional_service_region'])->group(function () {
    Route::get('/districts', function () {
        return view('pages.district.list');
    })->name('districts');
});

//Школы
Route::middleware(['role:correctional_service_district'])->group(function () {
    Route::get('/organizations', function () {
        return view('pages.organization.list');
    })->name('organizations');
});

//Отчеты по методикам
Route::get('/survey-reports', function () {
    return view('pages.survey.list');
})->name('survey.reports');
