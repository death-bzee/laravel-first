@props(['student', 'photoData', 'photoMimeType', 'defaultPhotoData', 'defaultPhotoMimeType'])

@include('components.partials.pdf.header', ['title' => $student->fullName])

<div class="p-8 container">
    <h1 class="text-center text-xl font-bold mb-6">{{ __('Форма') }}</h1>
    <p class="mb-6">{{ __('Министерство просвещения Республики Казахстан') }}</p>

    <div class="flex justify-between items-start mb-6">
        <div>
            <p class="text-center text-2xl font-bold">{{ __('Личное дело обучающегося № ________') }}</p>
        </div>
        <div>
            @if($photoData)
                <img src="data:{{ $photoMimeType }};base64,{{ $photoData }}" alt="{{ $student->fullName }}" class="w-32 h-32 rounded-full img-rounded">
            @else
                <img src="data:{{ $defaultPhotoMimeType }};base64,{{ $defaultPhotoData }}" alt="{{ $student->fullName }}" class="w-32 h-32 rounded-full img-rounded">
            @endif
        </div>
    </div>

    <p><strong>{{ __('Фамилия') }}:</strong> {{ $student->surname }}</p>
    <p><strong>{{ __('Имя') }}:</strong> {{ $student->name }}</p>
    <p><strong>{{ __('Отчество (при его наличии)') }}:</strong> {{ $student->patronymic ?? __('Нет') }}</p>
    <p><strong>{{ __('Число, месяц, год рождения') }}:</strong> {{ $student->birthdayFormatted }}</p>
    <p><strong>{{ __('Домашний адрес') }}:</strong> {{ $student->address }}</p>

    <p><strong>{{ __('1. Пол:') }}</strong> {{ $student->gender ? $student->gender->getLabel() : __('Не указан') }}</p>
    <p><strong>{{ __('2. Родился') }}:</strong> {{ $student->birthdayFormatted }}</p>
    <p><strong>{{ __('Основание:') }}</strong></p>
    <p>{{ __('Свидетельство о рождении №') }} {{ $student->birth_certificate ?? '___' }}
        {{ __('от') }} {{ $student->birth_certificate_date ? $student->birth_certificate_date->format('d.m.Y') : '__________' }}
        {{ __('серия №') }} {{ $student->birth_certificate_series ?? '__________' }}</p>

    <p><strong>{{ __('3. Фамилия, имя, отчество (при его наличии) родителей или других законных представителей ребенка:') }}</strong> {{ $student->parents_names }}</p>
    <p><strong>{{ __('4. Национальность') }}:</strong> {{ optional($student->nationality)->title }}</p>
    <p><strong>{{ __('5. Где воспитывался / обучался до поступления в первый класс') }}:</strong></p>
    <p>{{ $student->previous_education }}</p>
    <p><strong>{{ __('6. Отметка о выбытии из организации среднего образования: когда, куда, причины') }}:</strong></p>
    <p>{{ $student->departure_mark }}</p>

    <div class="text-sm text-gray-600">
        {!! __('messages.personal_file_note') !!}
    </div>

</div>

@include('components.partials.pdf.footer')
