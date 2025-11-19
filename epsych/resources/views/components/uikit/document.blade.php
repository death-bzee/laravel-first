@props(['document', 'truncateLength' => null])

@php
    $fileName = $document->name;
    if ($truncateLength && mb_strlen($fileName) > $truncateLength) {
        $fileName = mb_substr($fileName, 0, $truncateLength) . '...';
    }
@endphp

<li class="flex items-center gap-2">
    <img src="{{ asset("images/extensions/{$document->extension}.svg") }}" alt="{{ $document->extension }}" class="w-6 h-6">
    <x-link href="{{ $document->getUrl() }}" target="_blank" :navigate="false" download="{{ $document->name }}" class="text-sm">
        {{ $fileName }}
    </x-link>
</li>
