@props([
    'id',
    'groupId',
    'tmpDocuments' => [],
    'storedDocuments' => [],
    'accept' => 'application/pdf, image/jpeg',
    'name' => ''
])

@if($tmpDocuments || $storedDocuments)
    <div class="flex flex-wrap gap-4 mt-4">
        @foreach($tmpDocuments as $index => $document)
            <x-icon-link
                href="#"
                icon="fi fi-rs-circle-cross"
                color="text-red-500"
                class="text-zinc-950 hover:text-zinc-700"
                x-on:click.prevent="$wire.deleteTmpDocument({{ $groupId }}, {{ $index }})"
            >
                {{ $document->getClientOriginalName() }}
            </x-icon-link>
        @endforeach
        @foreach($storedDocuments as $index => $document)
            <x-icon-link
                href="#"
                icon="fi fi-rs-circle-cross"
                color="text-red-500"
                class="text-zinc-950 hover:text-zinc-700"
                x-on:click.prevent="$wire.deleteStoredDocument({{ $document['id'] }}, {{ $groupId }}, {{ $index }})"
            >
                {{ $document['original_name'] }}
            </x-icon-link>
        @endforeach
    </div>
@endif

<x-input-file id="{{ $id }}" name="{{ $name }}" accept="{{ $accept }}" />
