@props(['href', 'styleBtn' => 'primary'])

@php
    // Общие стили для всех кнопок
    $baseClasses = '
        text-nowrap
        inline-flex
        items-center
        px-4
        py-2
        rounded-md
        font-semibold
        text-xs
        uppercase
        tracking-widest
        focus:outline-none
        focus:ring-2
        focus:ring-primary
        focus:ring-offset-2
        transition
        ease-in-out
        duration-150
        h-10';

    // Специфические стили для primary и secondary кнопок
    $buttonStyles = [
        'primary' => 'bg-primary text-white border-transparent hover:bg-primary-light hover:text-white active:bg-primary-dark focus:bg-primary-light',
        'secondary' => 'bg-white text-primary border-2 border-primary hover:bg-primary hover:text-white active:bg-primary-dark focus:bg-primary-light',
    ];

    // Определяем финальный класс для кнопки
    $classes = $baseClasses . ' ' . ($buttonStyles[$styleBtn] ?? $buttonStyles['primary']);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    {{ $slot }}
</a>
