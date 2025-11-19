<div class="p-6 text-center">
    <h2 class="text-xl font-bold mb-4">Код доступа</h2>

    {{-- Показываем переданный короткий код --}}
    <div id="short-code-display" class="text-3xl font-mono tracking-widest text-indigo-600 mb-4">
        {{ $code }}
    </div>

    {{-- Кнопка копирования --}}
    <button type="button"
        onclick="
            const shortCode = '{{ $code }}';
            navigator.clipboard.writeText(shortCode).then(() => {
                document.getElementById('copy-message').classList.remove('hidden');
            });
        "
        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        Скопировать
    </button>

    {{-- Сообщение о копировании --}}
    <div id="copy-message" class="hidden mt-3 text-green-600 font-semibold">
        ✅ Скопировано
    </div>
</div>
