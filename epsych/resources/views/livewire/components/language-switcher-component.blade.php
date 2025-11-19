<div class="flex items-center space-x-2 lg:mr-5">
    <i class="fi fi-sr-globe text-primary mr-1"></i>
    @foreach ($availableLocales as $locale => $properties)
        <a href="#" wire:click.prevent="switchLanguage('{{ $locale }}')"
           class="{{ $locale === $currentLocale ? 'text-gray-400' : 'text-black' }} text-sm font-medium transition hover:text-gray-700">
            {{ $properties['native'] }}
        </a>
        @if (!$loop->last)
            <span class="text-gray-300">/</span>
        @endif
    @endforeach
</div>
