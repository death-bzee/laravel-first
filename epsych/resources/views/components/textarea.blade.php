<!-- Компонент Textarea -->
@props(['disabled' => false, 'rows' => 5, 'placeholder' => ''])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'disabled:bg-gray-50 disabled:opacity-50 border-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md placeholder-gray-300']) !!}
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"></textarea>
