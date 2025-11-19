@props(['student', 'photoData', 'photoMimeType', 'defaultPhotoData', 'defaultPhotoMimeType'])

@include('components.partials.pdf.header')

<div class="p-8 container">
    <div class="flex flex-col gap-6">
        <div class="flex gap-8 items-center">
            <div>
                @if($photoData)
                    <img src="data:{{ $photoMimeType }};base64,{{ $photoData }}" alt="{{ $student->fullName }}" class="w-32 h-32 rounded-full img-rounded">
                @else
                    <img src="data:{{ $defaultPhotoMimeType }};base64,{{ $defaultPhotoData }}" alt="{{ $student->fullName }}" class="w-32 h-32 rounded-full img-rounded">
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold">{{ $student->fullName }}</h1>
                <p class="mt-2">{!! $student->general_characteristics !!}</p>
            </div>
        </div>
        <table class="table mt-6">
            <tbody>
                <tr>
                    <td>{{ __('Организация') }}</td>
                    <td>{{ $student->organization->title }}</td>
                </tr>
                <tr>
                    <td>{{ __('ИИН') }}</td>
                    <td>{{ $student->iin }}</td>
                </tr>
                <tr>
                    <td>{{ __('Дата рождения') }}</td>
                    <td>{{ $student->birthdayFormatted }}</td>
                </tr>
                <tr>
                    <td>{{ __('Телефон') }}</td>
                    <td>{{ $student->phone }}</td>
                </tr>
                <tr>
                    <td>{{ __('Класс') }}</td>
                    <td>{{ $student->classroom->classroomName }}</td>
                </tr>
                <tr>
                    <td>{{ __('Язык обучения') }}</td>
                    <td>{{ $student->language->title }}</td>
                </tr>
                <tr>
                    <td>{{ __('Особые отметки') }}</td>
                    <td>{!! $student->specialMarks !!}</td>
                </tr>
                <tr>
                    <td>{{ __('Приводы в полицию, инциденты') }}</td>
                    <td>{!! $student->incidents !!}</td>
                </tr>
                <tr>
                    <td>{{ __('Особый статус') }}</td>
                    <td>{{ implode(', ', $student->specialStatusesList) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@include('components.partials.pdf.footer')
