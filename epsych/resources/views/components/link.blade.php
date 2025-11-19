@props(['href', 'navigate' => true])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'transition-all text-primary hover:opacity-50']) }}
   @if($navigate) wire:navigate @endif>
    {{ $slot }}
</a>
