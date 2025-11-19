<?php

namespace App\Providers;

use App\Models\ConsultationJournal;
use App\Models\Event;
use App\Models\SocialWorkPlan;
use App\Models\Student;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyGroupAssignment;
use App\Models\WorkPlan;
use App\Policies\ConsultationJournalPolicy;
use App\Policies\EventPolicy;
use App\Policies\SocialWorkPlanPolicy;
use App\Policies\StudentPolicy;
use App\Policies\Survey\SurveyAssignmentPolicy;
use App\Policies\Survey\SurveyGroupAssignmentPolicy;
use App\Policies\WorkPlanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        SurveyGroupAssignment::class => SurveyGroupAssignmentPolicy::class,
        SurveyAssignment::class => SurveyAssignmentPolicy::class,
        Event::class => EventPolicy::class,
        Student::class => StudentPolicy::class,
        WorkPlan::class => WorkPlanPolicy::class,
        SocialWorkPlan::class => SocialWorkPlanPolicy::class,
        ConsultationJournal::class => ConsultationJournalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Дополнительные правила или проверки через Gate
    }
}
