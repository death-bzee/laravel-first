<div>
    <div class="flex flex-col gap-y-10">
        <div class="flex flex-col">
            <x-link href="{{ route($this->link) }}" wire:navigate>{{ $this->material->materialType->title }}</x-link>
            <x-h1 class="mt-4 mb-0">{{ $this->material->title }}</x-h1>
        </div>
        @if($this->material->text)
            <div class="prose max-w-none columns-1 md:columns-2">
                {!! $this->material->text !!}
            </div>
            <hr>
        @endif
        @if($this->videos)
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4" uk-lightbox="video-autoplay: true">
                @foreach($this->videos as $video)
                    <div class="relative group">
                        <a href="{{ $video['url'] }}" class="block" data-attrs="width: 1280; height: 720;">
                            <img
                                class="absolute inset-0 m-auto w-14 h-14 transition-transform duration-200 transform group-hover:scale-95 z-10"
                                src="{{ asset('images/youtube.svg') }}"
                                alt="YouTube Icon SVG">
                            <img
                                class="w-full h-auto rounded-xl transition-transform duration-200 transform group-hover:scale-95"
                                src="{{ $video['thumbnail'] }}" alt="">
                        </a>
                    </div>
                @endforeach
            </div>
            <hr>
        @endif
        @if($this->material->images)
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4" uk-lightbox>
                @foreach($this->material->images as $image)
                    <div class="relative group overflow-hidden">
                        <a href="/storage/{{ $image }}" class="block">
                            <img
                                class="w-full h-auto rounded-xl transition-transform duration-200 transform group-hover:scale-95"
                                src="{{ asset("storage/$image") }}" alt="">
                        </a>
                    </div>
                @endforeach
            </div>
            <hr>
        @endif
        @if($this->files)
            <div class="flex flex-wrap gap-4">
                @foreach($this->files as $file)
                    <a href="{{ asset('storage/' . $file['url']) }}"
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors duration-200" download>
                        <img class="w-10 h-10" src="{{ asset("images/extensions/{$file['extension']}.svg") }}"
                             alt="{{ $file['extension'] }}">
                        <div class="ml-4 text-blue-600">{{ $file['original_name'] }}</div>
                    </a>
                @endforeach
            </div>
            <hr>
        @endif
    </div>
</div>
