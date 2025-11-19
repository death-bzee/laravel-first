<div>
	<form class="flex flex-col gap-6 md:gap-10" wire:submit="save">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<div>
				<x-label for="form.title" value="{{ __('Название мероприятия') }}" required />
				<x-input class="block mt-2 mb-2 w-full" wire:model="form.title" placeholder="Тестирование, например" />
				<x-input-error for="form.title" />
			</div>
			<div>
				<x-label for="form.event_date" value="{{ __('Дата мероприятия') }}" required />
				<x-input class="block mt-2 mb-2 w-full" wire:model="form.event_date" placeholder="07.08.1988"
						 x-mask="99.99.9999" />
				<x-input-error for="form.event_date" />
			</div>
			<div>
				<x-label for="form.classroom_id" value="{{ __('Класс') }}" required />
				<x-select2 name="form.classroom_id" options="form.classRooms" live />
				<x-input-error for="form.classroom_id" />
			</div>
            @if($this->form->classroom_id)
                <div>
                    <x-label for="form.student_selected" value="{{ __('Если нужно, можете выбрать только конкретных учеников') }}" />
                    <x-select2 name="form.student_selected" options="form.students" multiple watch />
                    <x-input-error for="form.student_selected" />
                </div>
            @endif
			<div>
				<x-label for="form.event_status_id" value="{{ __('Статус мероприятия') }}" />
				<x-select2 name="form.event_status_id" options="form.eventStatuses" />
				<x-input-error for="form.event_status_id" />
			</div>
			<div>
				<x-label for="form.tmpDocuments.5" value="{{ __('Файлы/материалы') }}" />
				<x-uikit.group-document
                    id="materials"
                    :groupId="5"
                    :tmpDocuments="$form->tmpDocuments[5]"
                    :storedDocuments="$form->storedDocuments[5]"
                    name="form.tmpDocuments.5"
				/>
				<x-input-error for="form.tmpDocuments.5.*" />
			</div>
			<div class="flex gap-4 col-span-full mt-4">
				<x-button>
					{{ __('Сохранить') }}
				</x-button>
				<x-button-link href="{{ route('events') }}" styleBtn="secondary">{{ __('Отмена') }}</x-button-link>
			</div>
		</div>
	</form>
</div>
