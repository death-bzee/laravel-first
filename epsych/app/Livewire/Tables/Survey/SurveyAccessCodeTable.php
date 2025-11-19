<?php

namespace App\Livewire\Tables\Survey;

use App\Contracts\User\UserRoleServiceContract;
use App\Models\Survey\SurveyAssignment;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SurveyAccessCodeTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'AccessTokeStudentSurveyTable';
    public ?int $surveyGroupAssignmentId = null;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        // Получаем список ID организаций пользователя
        $organizationIds = app(UserRoleServiceContract::class)->getOrganizationsByUser();

        // Если массив пустой, сразу возвращаем "пустой" запрос
        if (empty($organizationIds)) {
            return SurveyAssignment::query()->whereRaw('1 = 0'); // Никогда не найдёт записей
        }

        return SurveyAssignment::query()
            ->where('group_id', $this->surveyGroupAssignmentId)
            ->whereHas('groupAssignment', function ($query) use ($organizationIds) {
                $query->whereIn('organization_id', $organizationIds);
            })
            ->with(['accessTokenCode', 'student']);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('access_token_id')
            ->add('survey_assignment_id')
            ->add('student.surname')
            ->add('student.name')
            ->add('student.patronymic')
            ->add('accessTokenCode.access_code', fn($model) => e(substr($model->accessTokenCode->access_code, 0, 3) . '-' . substr($model->accessTokenCode->access_code, 3)));
    }

    public function columns(): array
    {
        return [
            Column::make(__('Фамилия'), 'student.surname')
                ->sortable()
                ->searchable(),
            Column::make(__('Имя'), 'student.name')
                ->sortable()
                ->searchable(),
            Column::make(__('Отчество'), 'student.patronymic')
                ->sortable()
                ->searchable(),
            Column::make('Код доступа', 'accessTokenCode.access_code')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('student.surname')
                ->filterRelation('student', 'surname')
                ->operators(['contains']),
            Filter::inputText('student.name')
                ->filterRelation('student', 'name')
                ->operators(['contains']),
            Filter::inputText('student.patronymic')
                ->filterRelation('student', 'patronymic')
                ->operators(['contains']),
            Filter::inputText('accessTokenCode.access_code')
                ->filterRelation('accessTokenCode', 'access_code')
                ->operators(['contains']),
        ];
    }

}
