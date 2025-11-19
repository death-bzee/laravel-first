@props(['documents'])

<div class="flex flex-wrap gap-4 mt-4">
    @forelse($documents as $document)
        <a href="{{ $document->getFullUrl() }}"
           class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors duration-200"
           download>
            <img class="w-10 h-10" src="{{ asset("images/extensions/{$document->extension}.svg") }}"
                 alt="{{ $document->extension }}">
            <div class="ml-4 text-blue-600">{{ $document->name }}</div>
        </a>
    @empty
        <p>{{ __('Документы не найдены') }}</p>
    @endforelse
</div>
