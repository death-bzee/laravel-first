<?php

namespace App\Livewire\Content\Organization;

use App\Contracts\Organization\SocialPassportServiceContract;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class SocialPassportSchoolContent extends Component
{
    public ?Collection $socialPassportData = null;

    public int $countStudents;

    public int $countSocialStudents;

    public ?Collection $educationLevelData = null; // Добавляем переменную для уровней образования

    public function mount(?int $organizationId, SocialPassportServiceContract $socialPassportServiceContract): void
    {
        if (empty($organizationId)) {
            $this->redirectRoute('scaling-diagram');

            return; // <== обязательно!
        }

        $this->countStudents = Student::query()->where('organization_id', $organizationId)->count();
        $this->socialPassportData = $socialPassportServiceContract->getSocialPassportSummary($organizationId);
        $this->countSocialStudents = $socialPassportServiceContract->getSocialCountStudents($organizationId);
        $this->educationLevelData = $socialPassportServiceContract->getStudentsGroupedByEducationLevel($organizationId);
    }

    public function render(): View
    {
        return view('livewire.content.organization.social-passport-school-content', [
            'socialPassportData' => $this->socialPassportData,
            'countStudents' => $this->countStudents,
            'countSocialStudents' => $this->countSocialStudents,
            'educationLevelData' => $this->educationLevelData, // Передаем данные в представление
        ]);
    }
}
