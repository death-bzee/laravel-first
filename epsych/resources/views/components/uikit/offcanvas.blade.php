<div id="mobile-menu" uk-offcanvas>
    <div class="uk-offcanvas-bar bg-secondary flex flex-col gap-8">

        <button class="uk-offcanvas-close" type="button" uk-close></button>

        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.svg') }}" class="w-42 md:w-35 lg:w-52">
        </a>
        <livewire:components.project-menu-component />

        <div>
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <a href="{{ route('profile.show') }}"
                    class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                         alt="{{ Auth::user()->name }}" />
                    <span class="ml-4 font-medium text-white">{{ __('Настройка профиля') }}</span>
                </a>
            @endif
        </div>

    </div>
</div>
