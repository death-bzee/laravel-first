<h3 class="mt-8 mb-2 font-semibold text-lg">
    {{ __('Общий свод по пройденным методикам по району') }}
    {{ $selectedDistrictTitle ? '(' . $selectedDistrictTitle . ')' : '' }}
</h3>

<div class="overflow-x-auto border rounded-md">
    <table class="min-w-full border-collapse text-sm text-center whitespace-nowrap">
        <thead class="bg-gray-100">
            @include('components.survey.report.methodic-header')
        </thead>
        <tbody>
            @foreach ($schoolStats as $school)
                @include('components.survey.report.row-methodic-table', ['school' => $school])
            @endforeach
            @include('components.survey.report.table-footer')
        </tbody>
    </table>
</div>
