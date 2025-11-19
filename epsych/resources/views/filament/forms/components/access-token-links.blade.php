@php
    $baseUrl = config('app.url'); // Получаем базовый URL из APP_URL
    $eventsLink = "{$baseUrl}/ru/access/events/{$getRecord()->token}"; // Используем токен из записи
    $dashboardLink = "{$baseUrl}/ru/access/dashboard/{$getRecord()->token}"; // Используем токен из записи
@endphp

<div>
    <a href="{{ $eventsLink }}" target="_blank" class="block text-blue-500 hover:text-blue-300">
        {{ $eventsLink }}
    </a>
    <a href="{{ $dashboardLink }}" target="_blank" class="block text-blue-500 hover:text-blue-300">
        {{ $dashboardLink }}
    </a>
</div>
