<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Qaz Sauat') }}</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
			integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<link href="{{ asset('css/vendors/select2.min.css') }}" rel="stylesheet">
        <script src="{{ asset('js/vendors/select2.min.js') }}"></script>

        @filamentStyles
        @livewireStyles
        @vite('resources/css/app.css')
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <x-partials.guest-header />

        <div>
            {{ $slot }}
        </div>

        <x-partials.footer />

        @stack('scripts')

        @filamentScripts
        @livewireScripts
        @vite('resources/js/app.js')
    </body>
</html>
