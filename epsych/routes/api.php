<?php

use App\Http\Controllers\Api\ActivityLogController;

Route::prefix('logs')->group(function () {
    Route::get('/daily', [ActivityLogController::class, 'daily']);
    Route::get('/monthly', [ActivityLogController::class, 'monthly']); // ✅
});
