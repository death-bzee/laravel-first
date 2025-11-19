<?php

namespace App\Jobs\Survey;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ClearSurveyAssignmentCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $ids;

    /**
     * Create a new job instance.
     *
     * @param array|int $ids
     */
    public function __construct(array|int $ids)
    {
        $this->ids = (array) $ids; // Приводим к массиву, если передан одиночный id
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach ($this->ids as $id) {
            $cacheKey = 'survey_assignment_' . $id;
            Cache::forget($cacheKey);
        }
    }
}
