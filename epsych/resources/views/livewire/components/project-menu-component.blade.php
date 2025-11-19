<div class="flex flex-col gap-2">
    @foreach($menus as $menu)
        @if ($menu->parent_id === null && $this->isAccessible($menu))
            @php
                $isParentActive = $this->isParentActive($menu);
                $isHasChild = $this->hasChild($menu->id);
            @endphp

            <x-menu-item
                id="{{ $menu->id }}"
                href="{{ $menu->link === '#' || !Route::has($menu->link) ? $menu->link : route($menu->link) }}"
                icon="{{ $menu->icon }}"
                class="{{ $isParentActive ? 'text-white hover:text-white bg-gray-800' : 'text-gray-400 hover:text-white' }}"
                child="{{ (bool) $isHasChild }}"
            >
                <div class="flex justify-between items-center">
                    {{ $menu->title }}
                    @if ($isHasChild)
                        <i class="fi fi-br-angle-small-down" style="line-height: 0; font-size: 20px"></i>
                    @endif
                </div>
            </x-menu-item>

            @if ($isHasChild)
                <div class="flex flex-col gap-2 pl-5 {{ $isParentActive ? 'flex' : 'hidden' }}" id="menu-item-{{ $menu->id }}">
                    @foreach ($menus->where('parent_id', $menu->id) as $submenu)
                        @if ($this->isAccessible($submenu))
                            @php
                                $isActive = $this->isActive($submenu);
                            @endphp

                            <x-menu-item
                                id="{{ $submenu->id }}"
                                href="{{ Route::has($submenu->link) ? route($submenu->link) : $submenu->link }}"
                                icon="{{ $submenu->icon }}"
                                class="{{ $isActive ? 'text-white hover:text-white bg-gray-800' : 'text-gray-400 hover:text-white' }}"
                            >
                                {{ $submenu->title }}
                            </x-menu-item>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    @endforeach
</div>
