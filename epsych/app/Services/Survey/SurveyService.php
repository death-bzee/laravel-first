<?php

namespace App\Services\Survey;

use App\Contracts\Survey\SurveyServiceContract;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyResult;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Survey\SurveyRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SurveyService implements SurveyServiceContract
{

    /**
     * Получает список тестов по классу, в котором учатся студенты.
     *
     * @param int|null $classroomId
     * @return Collection
     */
    public function getSurveysByClassroom(?int $classroomId = null): Collection
    {
        $organizationId = auth()->user()->organization_id;

        // Получаем список студентов по организации и классу через app()
        $students = app(StudentRepository::class)->getStudents($organizationId, $classroomId);

        $studentIds = $students->keys()->toArray();

        if (empty($studentIds)) {
            return collect(); // Если студентов нет, возвращаем пустую коллекцию
        }

        // Получаем список тестов, назначенных этим студентам через app()
        return app(SurveyRepository::class)->getSurveysByStudentIds($studentIds);
    }

    /**
     * Получает активное назначение опроса для студента и кэширует.
     *
     * @param int $survey_assignment_id
     * @return Model|Builder|null
     */
    public function getSurveyAssignment(int $survey_assignment_id): Model|Builder|null
    {
        return SurveyAssignment::where('id', $survey_assignment_id)->first();
    }

    /**
     * Метод возвращает номер последнего отвеченного вопроса, если нет отвеченных, то возвращает 0
     *
     * @param SurveyAssignment $surveyAssignment
     * @return int
     */
    public function getLastNumberAnsweredQuestion(SurveyAssignment $surveyAssignment): int
    {
        $lastQuestion = SurveyResult::where('survey_assignment_id', $surveyAssignment->id)
            ->with('question')
            ->get()
            ->sortByDesc('question.number')
            ->first();

        return $lastQuestion->question->number ?? 0;
    }

    public function getNextNumberQuestion(SurveyAssignment $surveyAssignment): int
    {
        return $this->getLastNumberAnsweredQuestion($surveyAssignment) + 1;
    }

    public function getPreviousNumberQuestion(SurveyAssignment $surveyAssignment): int
    {
        if ($this->getLastNumberAnsweredQuestion($surveyAssignment) > 0) {
            return $this->getLastNumberAnsweredQuestion($surveyAssignment) - 1;
        } else {
            return 1;
        }
    }

    public function setAnswer(array $data): void
    {
        // Извлекаем данные из массива
        $surveyAssignmentId = $data['survey_assignment_id'] ?? null;
        $questionId = $data['question_id'] ?? null;
        $optionIds = $data['option_ids'] ?? [];  // Может быть массивом для множественного выбора
        $studentId = $data['student_id'] ?? null;

        // Если `optionIds` не является массивом, приводим его к массиву для унификации логики
        if (!is_array($optionIds)) {
            $optionIds = [$optionIds];
        }

        // Удаляем старые ответы для этого вопроса
        SurveyResult::where('survey_assignment_id', $surveyAssignmentId)
                    ->where('question_id', $questionId)
                    ->delete();

        // Сохраняем новые ответы
        foreach ($optionIds as $optionId) {
            SurveyResult::create([
                'survey_assignment_id' => $surveyAssignmentId,
                'question_id' => $questionId,
                'option_id' => $optionId,
                'student_id' => $studentId,
            ]);
        }
    }

    public function setSurveyComplete(SurveyAssignment $surveyAssignment): void
    {
        $surveyAssignment->update([
            'completed_at' => now(),
        ]);

        if (!$surveyAssignment->exists) {
            throw new \RuntimeException('SurveyAssignment не найден.');
        }
    }
}
