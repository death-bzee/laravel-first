@props(['href', 'icon', 'color' => 'text-primary'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center transition-all duration-300 ease-in-out']) }}>
    <i class="{{ $icon }} {{ $color }} hover:opacity-55 transition-all duration-300 ease-in-out w-5"></i>
    <span class="hover:opacity-55 transition-all duration-300 ease-in-out ml-2 text-sm">{{ $slot }}</span>
</a>
