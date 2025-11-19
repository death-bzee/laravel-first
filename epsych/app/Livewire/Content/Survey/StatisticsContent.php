<?php

namespace App\Livewire\Content\Survey;

use App\Contracts\User\UserRoleServiceContract;
use App\Models\Survey\SurveyGroupAssignment;
use App\Models\Survey\SurveyStudentDiagnosis;
use Illuminate\View\View;
use Livewire\Component;

class StatisticsContent extends Component
{
    public array $statistics = [
        'total_answers' => 0,
        'completed' => false,
        'scaling' => [],
        'surveys' => []
    ];
    public array $studentData = [];
    public ?int $classroomId = null;

    public function mount($id): void
    {
        $this->classroomId = (int)$id;

        // Check organization access
        $organizationIds = app(UserRoleServiceContract::class)->getOrganizationsByUser();

        // Get all survey group assignments for this classroom
        $surveyGroups = SurveyGroupAssignment::with([
            'survey',
            'assignments.student',
            'assignments.studentDiagnosis',
            'classroom'
        ])
        ->where('classroom_id', $this->classroomId)
        ->whereIn('organization_id', $organizationIds)
        ->get();

        if ($classroom = $surveyGroups->first()?->classroom) {
            $this->studentData = [
                'grade' => $classroom->grade ?? '',
                'letter' => $classroom->letter ?? '',
                'surname' => '',
                'name' => '',
                'patronymic' => ''
            ];
        }

        $totalAnswers = 0;
        $hasCompleted = false;

        foreach ($surveyGroups as $group) {
            $completedAssignments = $group->assignments->filter(fn($a) => !is_null($a->completed_at));
            $totalAssigned = $group->assignments->count();
            $completedCount = $completedAssignments->count();

            $this->statistics['surveys'][] = [
                'title' => $group->survey->title,
                'total_assigned' => $totalAssigned,
                'completed_count' => $completedCount,
                'completion_rate' => $totalAssigned > 0 ? ($completedCount / $totalAssigned) * 100 : 0,
                'students' => $group->assignments->map(function($assignment) {
                    return [
                        'name' => $assignment->student->surname . ' ' . $assignment->student->name,
                        'status' => $assignment->completed_at ? 'completed' : 'pending',
                        'diagnosis' => $assignment->studentDiagnosis?->scaling ?? []
                    ];
                })->toArray()
            ];

            $totalAnswers += $totalAssigned;
            if ($completedCount > 0) {
                $hasCompleted = true;
            }

            // Get scaling from first completed diagnosis
            if (empty($this->statistics['scaling'])) {
                $firstCompleted = $completedAssignments->first();
                if ($firstCompleted && $firstCompleted->studentDiagnosis) {
                    $this->statistics['scaling'] = $firstCompleted->studentDiagnosis->scaling ?? [];
                }
            }
        }

        $this->statistics['total_answers'] = $totalAnswers;
        $this->statistics['completed'] = $hasCompleted;
    }

    public function render(): View
    {
        return view('livewire.content.survey.statistics-content');
    }
}
