<div>
	<form class="flex flex-col gap-6 md:gap-10" wire:submit="save">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-label for="form.group_id" value="{{ __('Группа теста') }}" required />
                <x-select2 name="form.group_id" options="form.groups" live />
                <x-input-error for="form.group_id" />
            </div>
            @if($this->form->group_id)
                <div>
                    <x-label for="form.student_id" value="{{ __('Ученик') }}" required />
                    <x-select2 name="form.student_id" options="form.students" :disabled="$this->form->isEdit" />
                    <x-input-error for="form.student_id" />
                </div>
            @endif
			<div class="flex gap-4 col-span-full mt-4">
				<x-button>
					{{ __('Сохранить') }}
				</x-button>
				<x-button-link href="{{ route('survey-assign') }}" styleBtn="secondary">{{ __('Отмена') }}</x-button-link>
			</div>
		</div>
	</form>
</div>
