<div>
    <form wire:submit="save()">
        <div class="grid grid-cols-1 gap-6">
        @if($students)
                <div>
                    <x-label for="students" value="{{ __('Ученики') }}" required />
                    <x-select2 name="student_selected_id" options="students" live />
                    <x-input-error for="student_selected_id" />
                </div>

                @if($student_selected_id)
                    <div>
                        <x-button>
                            {{ __('Начать тест') }}
                        </x-button>
                    </div>
                @endif
            @endif
        </div>
    </form>
</div>
