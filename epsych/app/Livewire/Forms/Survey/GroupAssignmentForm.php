<?php

namespace App\Livewire\Forms\Survey;

use App\Contracts\User\UserRoleServiceContract;
use App\Livewire\Forms\Objects\SurveyGroupAssignmentFormObject;
use App\Models\Survey\SurveyGroupAssignment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;
use Livewire\Component;

class GroupAssignmentForm extends Component
{
    public SurveyGroupAssignmentFormObject $form;

    public function mount(?int $surveyGroupAssignmentId = null): void
    {
        $surveyGroupAssignment = null;
        $organizationIds = app(UserRoleServiceContract::class)->getOrganizationsByUser();

        if ($surveyGroupAssignmentId) {
            try {
                // Загружаем запись, проверяя принадлежность к доступным организациям
                $surveyGroupAssignment = SurveyGroupAssignment::whereIn('organization_id', $organizationIds)
                    ->findOrFail($surveyGroupAssignmentId);

                $this->form->isEdit = true;
            } catch (ModelNotFoundException $e) {
                abort(404, 'Опрос не найден или у вас нет к нему доступа');
            }
        }

        $this->form->setData($surveyGroupAssignment);
        $this->form->setOptions();
    }

    /**
     * Метод вызывается при обновлении поля "group_id" в форме.
     *
     * Этот метод автоматически очищает выбранных студентов,
     * если значение поля "group_id" было изменено пользователем.
     *
     * @return void
     */
    public function updatedFormGroupId(): void
    {
        $this->form->student_selected = [];
    }

    /**
     * Метод вызывается при обновлении поля "classroom_id" в форме.
     *
     * Этот метод очищает список выбранных студентов и загружает новый список студентов,
     * основываясь на выбранном значении "classroom_id". Если "classroom_id" изменяется,
     * текущие выбранные студенты сбрасываются, и список студентов в форме обновляется
     * на основе нового значения.
     *
     * @param int $value Новое значение для "classroom_id".
     * @return void
     */
    public function updatedFormClassroomId(int $value): void
    {
        $this->form->student_selected = [];
        $this->form->students = [];
        $this->form->students = $this->form->getStudents(null, $value);
    }

    public function save(): void
    {
        if (auth()->user()->hasAnyRole(['psychologist', 'super_admin'])) {
            $this->form->save();
            $this->redirect('/survey-group-assign', navigate: true);
        } else {
            abort(403, __('Доступ запрещен'));
        }
    }

    public function render(): View
    {
        return view('livewire.forms.survey.group-assignment-form');
    }
}
