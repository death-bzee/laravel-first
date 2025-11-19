<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        @if (count($getFiles()) > 0)
            @foreach ($getFiles() as $file)
                <x-uikit.document :document="$file" />
            @endforeach
        @else
            <span class="text-gray-500">{{ __('Нет загруженных файлов') }}</span>
        @endif
    </div>
</x-dynamic-component>
