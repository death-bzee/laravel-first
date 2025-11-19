@props(['styleBtn' => 'primary', 'target' => 'save'])

@php
    // Определяем базовые стили для primary и secondary кнопок
    $primaryClasses = 'inline-flex items-center px-4 py-2 bg-primary text-white border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-primary-light focus:bg-primary-light active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150 h-10';
    $secondaryClasses = 'inline-flex items-center px-4 py-2 bg-white text-primary border border-primary rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-primary hover:text-white focus:bg-primary-light active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150 h-10';

    // Выбираем стили в зависимости от типа кнопки
    $classes = $styleBtn === 'secondary' ? $secondaryClasses : $primaryClasses;
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
    <span wire:loading.remove wire:target="{{ $target }}">{{ $slot }}</span>
    <span wire:loading wire:target="{{ $target }}">{{ __('Загрузка...') }}</span>
</button>
