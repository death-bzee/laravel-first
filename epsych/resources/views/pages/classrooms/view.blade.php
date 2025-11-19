<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('classrooms') }}" wire:navigate>{{ __('Классы') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Просмотр класса') }}</x-h1>
            </div>
        </div>
        <div>
            <livewire:tables.student.student-table :classroomId="$id"/>
        </div>
    </x-layouts.content-container>
</x-app-layout>
