@props(['id', 'title' => '', 'content' => ''])

<!-- This is the modal -->
<div id="{{ $id }}" uk-modal>
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
        <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
        <p class="text-gray-700 mt-6">{{ $content }}</p>
        <p class="text-right mt-6">
            <x-button class="uk-modal-close bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-4 rounded-lg">{{ __('Закрыть') }}</x-button>
        </p>
    </div>
</div>
