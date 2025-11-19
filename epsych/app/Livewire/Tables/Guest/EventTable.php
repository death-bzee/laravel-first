<?php

namespace App\Livewire\Tables\Guest;

use App\Models\AccessToken;
use App\Models\Concerns\EventStatus;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class EventTable extends PowerGridComponent
{
    public $token;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function datasource(): Builder
    {
        $this->token = Session::get('access_token');

        // Получаем список `organization_ids`, связанных с этим токеном
        $organizationIds = AccessToken::where('token', $this->token)
            ->with('organizations') // Загрузка связанных организаций
            ->first() // Получаем первую (или единственную) запись
            ->organizations
            ->pluck('id')
            ->toArray(); // Преобразуем в массив

        // Возвращаем фильтрованные события
        return Event::query()
            ->whereIn('organization_id', $organizationIds)
            ->leftJoin('classrooms', 'events.classroom_id', '=', 'classrooms.id')
            ->select('events.*', 'classrooms.grade as classroom_grade', 'classrooms.letter as classroom_letter')
            ->with(['organization', 'classroom', 'eventStatus']);
    }

    public function relationSearch(): array
    {
        return [
            'organization' => [
                'title',
                'bin'
            ],
            'classroom' => [
                'grade',
                'letter'
            ],
            'event_status_id' => [
                'title'
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('title')
            ->add('organization.bin')
            ->add('classroom_grade')
            ->add('classroom_letter')
            ->add('eventStatus.title')
            ->add('event_date_formatted', fn(Event $model) => Carbon::parse($model->event_date)->format('d/m/Y'))
            ->add('created_at_formatted', fn(Event $model) => Carbon::parse($model->created_at)->format('d/m/Y'))
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Название'), 'title')
                ->sortable()
                ->searchable(),
            Column::make(__('БИН организации'), 'organization.bin')
                ->hidden(isHidden: true, isForceHidden: false),
            Column::make(__('Класс'), 'classroom_grade')
                ->sortable()
                ->searchable(),
            Column::make(__('Литера'), 'classroom_letter')
                ->sortable()
                ->searchable(),
            Column::make(__('Статус'), 'eventStatus.title'),
            Column::make(__('Дата мероприятия'), 'event_date_formatted', 'event_date')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
            Column::make(__('Дата создания'), 'created_at_formatted', 'created_at')
                ->hidden(isHidden: true, isForceHidden: false)
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('organization.bin')
                ->filterRelation('organization', 'bin')
                ->operators(['contains']),
            Filter::inputText('title')
                ->operators(['contains']),
            Filter::inputText('classroom_grade')
                ->filterRelation('classroom', 'grade')
                ->operators(['contains']),
            Filter::inputText('classroom_letter')
                ->filterRelation('classroom', 'letter')
                ->operators(['contains']),
            Filter::multiSelect('eventStatus.title', 'event_status_id')
                ->dataSource(EventStatus::all())
                ->optionLabel('title')
                ->optionValue('id'),
            Filter::datepicker('event_date'),
            Filter::datepicker('created_at'),
        ];
    }

    public function actionRules(): array
    {
        return [
            Rule::rows()
                ->when(fn($event) => Carbon::now()->diffInDays($event->event_date, false) <= 3 && Carbon::now()->diffInDays($event->event_date, false) >= 0)
                ->setAttribute('class', 'bg-red-50 hover:bg-red-100'),

            Rule::rows()
                ->when(fn($event) => Carbon::now()->diffInDays($event->event_date, false) < 0)
                ->setAttribute('class', 'bg-green-50 hover:bg-green-100'),
        ];
    }


}
