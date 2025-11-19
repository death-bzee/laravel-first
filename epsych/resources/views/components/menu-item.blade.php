@props(['id','href', 'icon', 'child' => false])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex flex-row gap-3 items-center transition-all duration-300 ease-in-out px-4 py-3 rounded-md hover:bg-gray-800']) }} @php echo $child ? 'uk-toggle="target: #menu-item-' . $id . '; cls: hidden; animation: uk-animation-fade"' : 'wire:navigate'; @endphp>
    <i class="{{ $icon }}" style="line-height: 0; font-size: 20px"></i>
    <div class="flex-1">{{ $slot }}</div>
</a>
