<?php

namespace App\Livewire\Forms\Objects;

use App\Actions\Event\CreateEventAction;
use App\Enums\Survey\SurveyGroupAssignmentTypeEnum;
use App\Models\Student;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyGroupAssignment;
use App\Traits\Classroom\HasClassrooms;
use App\Traits\Student\HasStudents;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;
use Livewire\Form;

class SurveyGroupAssignmentFormObject extends Form
{
    use HasStudents, HasClassrooms;

    /**
     * Экземпляр SurveyAssignment.
     *
     * @var SurveyGroupAssignment|null
     */
    public ?SurveyGroupAssignment $surveyGroupAssignment = null;

    /**
     * Заголовок группы тестов.
     *
     * @var string
     */
    public string $title = '';

    /**
     * Массив доступных классов.
     *
     * @var array
     */
    public array $classRooms = [];

    /**
     * Идентификатор выбранного класса.
     *
     * @var int|null
     */
    public ?int $classroom_id = null;

    /**
     * Массив типов опроса.
     *
     * @var array
     */
    public array $types = [];

    /**
     * Выбранный тип опроса.
     *
     * @var SurveyGroupAssignmentTypeEnum
     */
    public SurveyGroupAssignmentTypeEnum $type;

    /**
     * Массив методик тестов.
     *
     * @var array
     */
    public array $surveys = [];

    /**
     * Идентификатор опроса, связанного с заданием.
     *
     * @var int|null
     */
    public ?int $survey_id = null;

    /**
     * Массив студентов, доступных для выбора в задании.
     *
     * @var array
     */
    public array $students = [];

    /**
     * Идентификатор выбранного студента, если применимо.
     *
     * @var array|null
     */
    public ?array $student_selected = [];

    /**
     * Указывает, находится ли форма в режиме редактирования.
     *
     * @var bool
     */
    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'classroom_id' => 'required|integer|exists:classrooms,id',
            'type' => ['required', new Enum(SurveyGroupAssignmentTypeEnum::class)],
            'survey_id' => 'required||integer|exists:surveys,id',
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->surveyGroupAssignment && $this->surveyGroupAssignment->exists) {
            $this->updateSurveyGroupAssignment();
        } else {
            $students = $this->type === SurveyGroupAssignmentTypeEnum::Group
                ? $this->getStudentsByClassroomAndOrganization($this->classroom_id)
                : Student::whereIn('id', $this->student_selected)->get();

            $this->createSurveyGroupAssignment($students);
        }
    }

    protected function updateSurveyGroupAssignment(): void
    {
        $this->surveyGroupAssignment->update([
            'title' => $this->title,
        ]);
    }

    /**
     * Создает задание опроса в зависимости от выбранного типа.
     *
     * Если тип опроса — групповой, создаются задания для всех студентов в выбранном классе.
     * В противном случае, создается одно задание для указанных индивидуальных студентов.
     *
     * @param $students
     * @return void
     */
    protected function createSurveyGroupAssignment($students): void
    {
        $surveyGroupAssignment = SurveyGroupAssignment::create([
                'title' => $this->title,
                'organization_id' => auth()->user()->organization_id,
                'classroom_id' => $this->classroom_id,
                'type' => $this->type,
                'survey_id' => $this->survey_id,
            ]
        );

        if ($surveyGroupAssignment && $surveyGroupAssignment->exists) {
            $eventData = $this->prepareEvent();
            CreateEventAction::handle($eventData);

            $this->saveSurveyAssignments($students, $surveyGroupAssignment);
        }
    }

    protected function prepareEvent(): array
    {
        return [
            'title' => __('Тестирование') . ': ' . $this->title,
            'organization_id' => auth()->user()->organization_id,
            'classroom_id' => $this->classroom_id,
            'event_status_id' => 1,
            'event_date' => Carbon::now(),
        ];
    }

    /**
     * Сохраняет задания опросов для группы студентов.
     *
     * Метод принимает коллекцию объектов студентов, а также объект
     * SurveyGroupAssignment, и создает массив данных для массового сохранения в таблице SurveyAssignment.
     * Если массив заданий не пуст, выполняется массовое вставление записей в базу данных.
     *
     * @param Collection $students Коллекция объектов студентов.
     * @param SurveyGroupAssignment $surveyGroupAssignment Объект SurveyGroupAssignment, содержащий информацию о группе опроса.
     * @return void
     */
    private function saveSurveyAssignments(Collection $students, SurveyGroupAssignment $surveyGroupAssignment): void
    {
        foreach ($students as $student) {
            SurveyAssignment::create([
                'group_id' => $surveyGroupAssignment->id,
                'student_id' => $student->id,
                'assigned_at' => Carbon::now(),
            ]);
        }
    }

    public function setData(?SurveyGroupAssignment $surveyGroupAssignment = null): void
    {
        $this->surveyGroupAssignment = $surveyGroupAssignment ?: new SurveyGroupAssignment;

        if ($this->surveyGroupAssignment->exists) {
            $this->title = $this->surveyGroupAssignment->title;
            $this->classroom_id = $this->surveyGroupAssignment->classroom_id;
            $this->type = $this->surveyGroupAssignment->type;
            $this->survey_id = $this->surveyGroupAssignment->survey_id;

            // Получаем коллекцию заданий
            $assignments = $this->surveyGroupAssignment->assignments()->get();

            // Используем метод getStudents для получения списка студентов
            $this->students = $this->getStudents($assignments);

            // Выбранные студенты
            $this->student_selected = $assignments->pluck('student_id')->toArray();

        } else {
            $this->type = SurveyGroupAssignmentTypeEnum::Group;
            if ($this->classroom_id) {
                // Используем метод getStudents для получения списка студентов по classroom_id
                $this->students = $this->getStudents(null, $this->classroom_id);
            }
        }
    }

    public function setOptions(): void
    {
        $this->surveys = Survey::pluck('title', 'id')->toArray();
        $this->types = SurveyGroupAssignmentTypeEnum::options();
        $this->classRooms = $this->setClassRooms();

        if ($this->classroom_id) {
            $this->students = $this->getStudents(null, $this->classroom_id);
        }
    }

    /**
     * Получает список студентов на основе переданных заданий или идентификатора класса.
     *
     * @param Collection|null $assignments Коллекция заданий, связанных со студентами.
     * @param int|null $classroom_id Идентификатор класса для получения списка студентов.
     * @return array Ассоциативный массив, где ключами являются идентификаторы студентов, а значениями — их полные имена.
     */
    public function getStudents(Collection $assignments = null, int $classroom_id = null): array
    {
        $organization_id = auth()->user()->organization_id;

        if ($assignments) {
            // Предзагрузка отношения 'student' и фильтрация по organization_id
            $assignments->load(['student' => function ($query) use ($organization_id) {
                $query->where('organization_id', $organization_id);
            }]);

            return $this->mapStudents($assignments->pluck('student'));
        }

        if ($classroom_id) {
            return $this->mapStudents(
                Student::query()
                    ->where('classroom_id', $classroom_id)
                    ->where('organization_id', $organization_id)
                    ->get()
            );
        }

        return [];
    }

}
