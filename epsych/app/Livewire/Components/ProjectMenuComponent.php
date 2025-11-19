<?php

namespace App\Livewire\Components;

use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ProjectMenuComponent extends Component
{
    public $menus;

    public function mount(): void
    {
        $this->menus = Menu::where('is_active', true)
            ->orderBy('sort', 'asc')
            ->get();
    }

    public function isActive($menu): bool
    {
        // Проверяем, если ссылка это '#', то возвращаем false, так как это не маршрут
        if ($menu->link === '#') {
            return false;
        }

        // Проверяем, существует ли маршрут
        if (!Route::has($menu->link)) {
            return false;
        }

        $currentSegments = request()->segments();
        $menuSegments = explode('/', trim(parse_url(route($menu->link), PHP_URL_PATH), '/'));

        // Проверяем, совпадают ли начальные сегменты URL
        return count(array_intersect($currentSegments, $menuSegments)) == count($menuSegments);
    }

    public function hasActiveChild($menuId): bool
    {
        foreach ($this->menus as $menu) {
            if ($menu->parent_id === $menuId) {
                if ($this->isActive($menu) || $this->hasActiveChild($menu->id)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isParentActive($menu): bool
    {
        return $this->isActive($menu) || $this->hasActiveChild($menu->id);
    }

    public function hasChild($menuId): bool
    {
        foreach ($this->menus as $menu) {
            if ($menu->parent_id === $menuId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Проверяет, доступен ли пункт меню пользователю по его ролям.
     */
    public function isAccessible(Menu $menu): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $roles = $menu->roles ?? [];

        if (empty($roles)) {
            return true;
        }

        return $user->hasAnyRole($roles);
    }

    public function render(): View
    {
        return view('livewire.components.project-menu-component', [
            'menus' => $this->menus
        ]);
    }
}
