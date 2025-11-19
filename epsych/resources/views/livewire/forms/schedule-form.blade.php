<div>
    <form class="flex flex-col gap-6 md:gap-10" wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <x-label for="title" value="{{ __('Наименование мероприятия') }}" required />
                <x-input class="block mt-2 mb-2 w-full" wire:model="title" placeholder="Школьное собрание" />
                <x-input-error for="title" />
            </div>
            <div>
                <x-label for="date" value="{{ __('Дата мероприятия') }}" required />
                <x-input class="block mt-2 mb-2 w-full" wire:model="date" x-mask="99.99.9999" placeholder="{{ now()->format('d.m.Y') }}" />
                <x-input-error for="date" />
            </div>
            <div>
                <x-label for="status" value="{{ __('Статус') }}" required />
                <x-input class="block mt-2 mb-2 w-full" wire:model="status" placeholder="Олжас" />
                {{-- <x-select2 name="form.selected.class" multiple :options="$form->options['class']" />--}}
                <x-input-error for="status" />
            </div>
            <div class="col-span-full">
                <x-label for="form.tmpFiles" value="{{ __('Файлы / Материалы ') }}" required />
                <div class="flex flex-wrap gap-4 mt-4">
                    <x-icon-link
                        href="#"
                        icon="fi fi-rs-circle-cross"
                        color="text-red-500"
                        class="text-zinc-950 hover:text-zinc-700"
                        x-on:click="$wire.deleteStoredFile()">Имя файла</x-icon-link>

                    <x-icon-link
                        href="#"
                        icon="fi fi-rs-circle-cross"
                        color="text-red-500"
                        class="text-zinc-950 hover:text-zinc-700"
                        x-on:click="$wire.deleteStoredFile()">Имя файла</x-icon-link>
                </div>

                <x-input-file id="input" name="form.tmpFiles" accept="application/pdf, image/jpeg" />
                <x-input-error for="form.tmpFiles" />
            </div>

            <div class="flex gap-4 col-span-full mt-4">
                <x-button>{{ __('Сохранить') }}</x-button>
                <x-button-link href="{{ route('students') }}" styleBtn="secondary">{{ __('Отмена') }}</x-button-link>
            </div>
        </div>
    </form>
</div>
