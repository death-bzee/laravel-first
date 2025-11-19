<div>
	<form class="flex flex-col gap-6 md:gap-10" wire:submit="save">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
				<x-label for="form.title" value="{{ __('Заголовок') }}" required />
				<x-input class="block mt-2 mb-2 w-full" wire:model="form.title" placeholder="{{ __('Например, тестирование 7б класса') }}" />
				<x-input-error for="form.title" />
			</div>
            @if(!$this->form->isEdit)
                <div>
                    <x-label for="form.type" value="{{ __('Тип теста') }}" required />
                    <x-select2 name="form.type" options="form.types" live :disabled="$this->form->isEdit" />
                    <x-input-error for="form.type" />
                </div>
                <div>
                    <x-label for="form.survey_id" value="{{ __('Тест') }}" required />
                    <x-select2 name="form.survey_id" options="form.surveys" :disabled="$this->form->isEdit" />
                    <x-input-error for="form.survey_id" />
                </div>
                <div>
                    <x-label for="form.classroom_id" value="{{ __('Класс') }}" required />
                    <x-select2 name="form.classroom_id" options="form.classRooms" live :disabled="$this->form->isEdit" />
                    <x-input-error for="form.classroom_id" />
                </div>
                @if($this->form->type === \App\Enums\Survey\SurveyGroupAssignmentTypeEnum::Individual && $this->form->classroom_id)
                    <div>
                        <x-label for="form.student_selected" value="{{ __('Ученики') }}" required />
                        <x-select2 name="form.student_selected" options="form.students" multiple :disabled="$this->form->isEdit" watch />
                        <x-input-error for="form.student_selected" />
                    </div>
                @endif
            @endif
			<div class="flex gap-4 col-span-full mt-4">
				<x-button>
					{{ __('Сохранить') }}
				</x-button>
				<x-button-link href="{{ route('survey-group-assign') }}" styleBtn="secondary">{{ __('Отмена') }}</x-button-link>
			</div>
		</div>
	</form>
</div>
