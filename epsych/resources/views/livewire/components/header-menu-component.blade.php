<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    @foreach($menus as $menu)
        <x-nav-link href="{{ route($menu->link) }}" :active="request()->routeIs($menu->link)" wire:navigate>
            {{ $menu->title }}
        </x-nav-link>
    @endforeach
</div>
