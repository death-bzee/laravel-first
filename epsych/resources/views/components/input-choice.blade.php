@props([
    'type' => 'radio', // Тип по умолчанию - 'radio'
    'name' => '',
    'value' => '',
    'label' => '',
    'checked' => false, // Используем для определения выбрано ли значение по умолчанию
])

<label class="flex items-center gap-2">
    <input
        type="{{ $type }}"
        wire:model="{{ $name }}"
        value="{{ $value }}"
        @if(is_array($checked) && in_array($value, $checked)) checked @endif
        {{ $attributes->merge(['class' => 'rounded']) }}
    />
    {{ $label }}
</label>
