<?php

namespace App\Livewire\Forms\Objects;

use App\Models\Student;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyGroupAssignment;
use Illuminate\Support\Carbon;
use Livewire\Form;

class SurveyAssignmentFormObject extends Form
{
    /**
     * Экземпляр SurveyAssignment.
     *
     * @var SurveyAssignment|null
     */
    public ?SurveyAssignment $surveyAssignment = null;

    /**
     * Массив групп тестов, доступных для выбора в задании.
     *
     * @var array
     */
    public array $groups = [];

    /**
     * Идентификатор выбранной группы.
     *
     * @var int|null
     */
    public ?int $group_id = null;

    /**
     * Массив студентов, доступных для выбора в задании.
     *
     * @var array
     */
    public array $students = [];

    /**
     * Идентификатор выбранного студента, если применимо.
     *
     * @var int|null
     */
    public ?int $student_id = null;

    /**
     * Указывает, находится ли форма в режиме редактирования.
     *
     * @var bool
     */
    public bool $isEdit = false;

    /**
     * Правила валидации для формы.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'group_id' => 'required|integer|exists:survey_group_assignments,id',
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
                'unique:survey_assignments,student_id,NULL,id,group_id,' . $this->group_id,
            ],
        ];
    }

    /**
     * Метод сохранения формы при создании и редактировании записи
     *
     * @return void
     */
    public function save(): void
    {
        $this->validate();

        if ($this->surveyAssignment && $this->surveyAssignment->exists) {
            $this->updateSurveyAssignment();
        } else {
            $this->createSurveyAssignment();
        }
    }

    /**
     * Обновляет существующее задание опроса с новыми данными.
     *
     * Этот метод обновляет задание опроса, используя значения, выбранные или измененные
     * в форме, такие как идентификатор опроса, класс, тип опроса и идентификатор студента.
     * Метод применяется только к существующим заданиям опроса.
     *
     * @return void
     */
    protected function updateSurveyAssignment(): void
    {
        $this->surveyAssignment->update([
            'group_id' => $this->group_id,
            'assigned_at' => Carbon::now(),
        ]);
    }

    /**
     * Создает задание опроса в зависимости от выбранного типа.
     *
     * Если тип опроса — групповой, создаются задания для всех студентов в выбранном классе.
     * В противном случае, создается одно задание для индивидуального студента.
     *
     * Метод автоматически определяет, какой подход использовать, и вызывает соответствующий
     * метод для создания записи (или записей) в базе данных.
     *
     * @return void
     */
    protected function createSurveyAssignment(): void
    {
        SurveyAssignment::create([
            'group_id' => $this->group_id,
            'student_id' => $this->student_id,
        ]);
    }

    /**
     * Метод для загрузки начальных данных формы.
     *
     * @param SurveyAssignment|null $surveyAssignment
     * @return void
     */
    public function setData(?SurveyAssignment $surveyAssignment = null): void
    {
        $this->surveyAssignment = $surveyAssignment ?: new SurveyAssignment;

        $this->groups = SurveyGroupAssignment::where('organization_id', auth()->user()->organization_id)
            ->pluck('title', 'id')
            ->toArray();

        if ($this->surveyAssignment->exists) {
            $this->group_id = $this->surveyAssignment->group_id;
            $this->student_id = $this->surveyAssignment->student_id;
        }
    }

    /**
     * Метод для загрузки списка студентов выбранного класса
     *
     * @param $group_id
     * @return array
     */
    public function setStudents($group_id): array
    {
        if (is_null($group_id)) {
            return [];
        }

        // Найдем запись SurveyGroupAssignment по group_id
        $surveyGroupAssignment = SurveyGroupAssignment::find($group_id);

        if (!$surveyGroupAssignment) {
            return [];
        }

        // Получаем classroom_id из найденной записи
        $classroom_id = $surveyGroupAssignment->classroom_id;

        // Получаем список студентов по classroom_id
        return Student::where('classroom_id', $classroom_id)
            ->get()
            ->mapWithKeys(function ($student) {
                return [
                    $student->id => $student->surname . ' ' . $student->name . ' ' . $student->patronymic,
                ];
            })
            ->toArray();
    }


}
