<header class="w-full shadow-bottom bg-white" uk-sticky="show-on-up: true; animation: uk-animation-slide-top">
    <div class="h-18 mx-auto flex items-center justify-between px-6" style="height: 70px;">
        <div class="flex items-center gap-6">
            <a href="/" class="lg:hidden" wire:navigate>
                <img src="{{ asset('images/logo-for-white.svg') }}" alt="Logo E-Psycholog"
                     class="w-26 md:w-35 lg:w-40 h-10">
            </a>
            <livewire:components.language-switcher-component />
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden md:block">
                <x-partials.profile.profile-menu />
            </div>
            <x-icon-link href="#" uk-toggle="target: #mobile-menu" class="lg:hidden ml-6" icon="fi fi-br-menu-burger" color="text-primary" @click.prevent="$dispatch('mobile-menu')"></x-icon-link>
        </div>
    </div>

</header>
