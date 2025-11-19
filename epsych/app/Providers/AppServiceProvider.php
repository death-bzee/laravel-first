<?php

namespace App\Providers;

use App\Contracts\DocumentServiceContract;
use App\Contracts\MaterialServiceContract;
use App\Contracts\Organization\SocialPassportServiceContract;
use App\Contracts\QrCodeServiceContract;
use App\Contracts\Student\ClassroomServiceContract;
use App\Contracts\Student\StudentPdfServiceContract;
use App\Contracts\Student\StudentServiceContract;
use App\Contracts\Survey\Chart\SurveyChartDataServiceContract;
use App\Contracts\Survey\SurveyDiagnosisServiceContract;
use App\Contracts\Survey\SurveyGroupAssignmentServiceContract;
use App\Contracts\Survey\SurveyInterpretationServiceContract;
use App\Contracts\Survey\SurveyResultServiceContract;
use App\Contracts\Survey\SurveyScalingServiceContract;
use App\Contracts\Survey\SurveyServiceContract;
use App\Contracts\TranslateServiceContract;
use App\Contracts\UniqueCodeServiceContract;
use App\Contracts\User\UserPasswordExpirationServiceContract;
use App\Contracts\User\UserRoleServiceContract;
use App\Events\ImportStudentsCompleted;
use App\Services\DocumentService;
use App\Services\MaterialService;
use App\Services\Organization\SocialPassportService;
use App\Services\QrCodeService;
use App\Services\Student\ClassroomService;
use App\Services\Student\StudentPdfService;
use App\Services\Student\StudentService;
use App\Services\Survey\Ai\SurveyDiagnosisService;
use App\Services\Survey\Ai\SurveyInterpretationService;
use App\Services\Survey\Ai\SurveyScalingService;
use App\Services\Survey\Chart\SurveyChartDataService;
use App\Services\Survey\SurveyGroupAssignmentService;
use App\Services\Survey\SurveyResultService;
use App\Services\Survey\SurveyService;
use App\Services\TranslateService;
use App\Services\UniqueCodeService;
use App\Services\User\UserPasswordExpirationService;
use App\Services\User\UserRoleService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Services\Survey\Report\SurveyReportManager;
use App\Services\Survey\Report\OlweusSurveyReport;
use App\Services\Survey\Report\KakSToboyObrashSurveyReport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRoleServiceContract::class, UserRoleService::class);
        $this->app->bind(UserPasswordExpirationServiceContract::class, UserPasswordExpirationService::class);
        $this->app->bind(DocumentServiceContract::class, DocumentService::class);

        $this->app->bind(StudentPdfServiceContract::class, StudentPdfService::class);
        $this->app->bind(StudentServiceContract::class, StudentService::class);

        $this->app->bind(MaterialServiceContract::class, MaterialService::class);
        $this->app->bind(SocialPassportServiceContract::class, SocialPassportService::class);
        $this->app->bind(ClassroomServiceContract::class, ClassroomService::class);
        $this->app->singleton(TranslateServiceContract::class, TranslateService::class);

        $this->app->singleton(SurveyServiceContract::class, SurveyService::class);
        $this->app->bind(SurveyDiagnosisServiceContract::class, SurveyDiagnosisService::class);
        $this->app->bind(SurveyResultServiceContract::class, SurveyResultService::class);
        $this->app->singleton(SurveyScalingServiceContract::class, SurveyScalingService::class);
        $this->app->singleton(SurveyInterpretationServiceContract::class, SurveyInterpretationService::class);

        $this->app->singleton(SurveyChartDataServiceContract::class, SurveyChartDataService::class);

        $this->app->singleton(UniqueCodeServiceContract::class, UniqueCodeService::class);
        $this->app->singleton(SurveyGroupAssignmentServiceContract::class, SurveyGroupAssignmentService::class);

        $this->app->bind(QrCodeServiceContract::class, QrCodeService::class);

        $this->app->singleton(SurveyReportManager::class, function ($app) {
            return new SurveyReportManager([
                $app->make(OlweusSurveyReport::class),
                $app->make(KakSToboyObrashSurveyReport::class),
                // сюда потом добавишь другие отчёты (тревожность, жизнестойкость и т.д.)
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(ImportStudentsCompleted::class, function ($event) {
            Notification::make()
                ->title($event->message)
                ->success()
                ->send();
        });
    }
}
