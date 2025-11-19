<?php

namespace App\Livewire\Content\Survey;

use App\Contracts\User\UserRoleServiceContract;
use App\Jobs\Survey\Ai\GenerateSurveyDiagnosisJob;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyResult;
use App\Models\Survey\SurveyStudentDiagnosis;
use Illuminate\View\View;
use Livewire\Component;

class ResultContent extends Component
{
    public $surveyResult;

    public $diagnosis;

    public array $studentData = [];

    public ?int $surveyId = null;

    public ?string $scaling = null;

    public function mount($id): void
    {
        $this->surveyId = (int) $id;

        $record = SurveyAssignment::with('groupAssignment')->findOrFail($this->surveyId);
        $organizationIds = app(UserRoleServiceContract::class)->getOrganizationsByUser();

        if (! $record->groupAssignment || ! in_array($record->groupAssignment->organization_id, $organizationIds)) {
            abort(403, __('Доступ запрещен'));
        }

        $this->surveyResult = SurveyResult::where('survey_assignment_id', $this->surveyId)
            ->whereHas('surveyAssignment.groupAssignment', function ($query) use ($organizationIds) {
                $query->whereIn('organization_id', $organizationIds);
            })
            ->with(['surveyAssignment', 'question', 'option'])
            ->get();

        $this->studentData = $this->getStudentData();

        // Загружаем диагноз с уровнем сразу
        $this->diagnosis = SurveyStudentDiagnosis::with('levelValue')
            ->where('survey_assignment_id', $this->surveyId)
            ->first();

        $this->scaling = $this->formatScalingToHtml();
    }

    public function formatScalingToHtml(): ?string
    {
        if (! $this->diagnosis || empty($this->diagnosis->scaling)) {
            return null;
        }

        $html = '<ul>';
        foreach ($this->diagnosis->scaling as $item) {
            $html .= "<li><b>{$item['scaleName']}:</b> {$item['levelName']} <br><b>".__('Баллы')."</b>: {$item['score']}</li>";
        }
        $html .= '</ul>';

        return $html;
    }

    public function getStudentDiagnosis(): void
    {
        GenerateSurveyDiagnosisJob::dispatch($this->surveyId);
    }

    protected function getStudentData(): array
    {
        $row = $this->surveyResult->first();

        if ($row && $row->surveyAssignment) {
            return [
                'grade' => $row->surveyAssignment->group->classroom->grade,
                'letter' => $row->surveyAssignment->group->classroom->letter,
                'surname' => $row->surveyAssignment->student->surname,
                'name' => $row->surveyAssignment->student->name,
                'patronymic' => $row->surveyAssignment->student->patronymic,
            ];
        }

        return [];
    }

    public function render(): View
    {
        return view('livewire.content.survey.result-content', [
            'diagnosis' => $this->diagnosis,
            'scaling' => $this->scaling,
        ]);
    }
}
