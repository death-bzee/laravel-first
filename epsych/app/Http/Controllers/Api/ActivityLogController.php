<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function daily(Request $request)
    {
        $logs = Activity::query()
            ->where('created_at', '>=', now()->subDay())
            ->latest()
            ->get();

        return ActivityLogResource::collection($logs);
    }

    public function monthly(Request $request)
    {
        $logs = Activity::query()
            ->where('created_at', '>=', now()->subMonth())
            ->latest()
            ->get();

        return ActivityLogResource::collection($logs);
    }
}
