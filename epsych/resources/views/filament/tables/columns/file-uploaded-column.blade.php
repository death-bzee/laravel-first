<div class="space-y-1">
    @if (count($getFiles()) > 0)
        @foreach ($getFiles() as $file)
            <x-uikit.document :document="$file" />
        @endforeach
    @else
        <span class="text-gray-500">{{ __('Нет загруженных файлов') }}</span>
    @endif
</div>
