<header class="w-full px-6 shadow-sm" uk-sticky="show-on-up: true; animation: uk-animation-slide-top">
    <div class="h-18 max-w-8xl mx-auto flex items-center justify-between" style="height: 70px;">
        <div class="flex items-center space-x-4">
            <a href="/" wire:navigate>
                <img src="{{ asset('images/logo-for-white.svg') }}" alt="Logo E-Psycholog" class="w-36 md:w-35 lg:w-40 h-10">
            </a>
        </div>
        <div class="flex items-center space-x-4">

            <livewire:components.language-switcher-component />

            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end">
                    @auth
                        <x-button-link href="{{ url('/dashboard') }}" class="hidden md:inline-flex">
                            Дашборд
                        </x-button-link>
                    @else
                        <x-button-link href="{{ route('login') }}" class="hidden md:inline-flex">
                            {{ __('Войти') }}
                        </x-button-link>

                        <x-icon-link href="{{ route('login') }}" class="md:hidden" icon="fi fi-sr-user" color="text-primary"></x-icon-link>

                        {{--<x-icon-link href="#" class="md:hidden" icon="fi fi-br-menu-burger" color="text-primary ml-3" @click.prevent="$dispatch('mobile-menu')"></x-icon-link>--}}

                        @if (Route::has('register'))
                            <x-button-link href="{{ route('register') }}" styleBtn="secondary" class="ms-4 hidden md:inline-flex">
                                {{ __('Создать аккаунт') }}
                            </x-button-link>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </div>
</header>
