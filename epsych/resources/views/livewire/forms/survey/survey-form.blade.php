<div class="w-full h-full px-6 py-8 xl:py-16 bg-gray-100" uk-height-viewport="offset-top: true; offset-bottom: true">
    <div class="max-w-screen-xl mx-auto flex flex-col gap-10">
        <div class="flex flex-col gap-5">
            @if($this->form->surveyAssignment)
                <h3 class="font-bold mb-2">
                    {{ $this->form->survey->title }}
                </h3>
                @if(!$this->form->isSurveyComplete && $this->form->isSurveyStarted)
                    @if ($this->form->survey->images)
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            @foreach ($this->form->survey->images as $image)
                                <div>
                                    <img src="{{ asset('storage/' . $image) }}" alt="" class="rounded shadow max-w-full h-auto">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div>
                        {{ __('Тест проходит ученик :grade:letter класса :surname :name :patronymic', $this->form->studentData) }}
                    </div>
                    <div class="font-black">
                        {{ __('Вопрос') . ' ' . $this->form->currentQuestionNumber . ' ' . __('из') . ' ' . $this->form->questionCount}}
                    </div>
                @endif
            @endif
        </div>
        <div class="px-8 py-8 bg-white shadow-sm overflow-hidden rounded-lg">
            @if($this->form->isSurveyStarted && !$this->form->isSurveyComplete)
                <form class="flex flex-col gap-6" wire:submit="nextQuestion">
                    <div class="font-black mb-4">
                        {{ $this->form->currentQuestion->title ?? '' }}
                    </div>
                    <div class="flex flex-col gap-4">
                        @if($this->form->currentQuestion->type === \App\Enums\Survey\SurveyQuestionTypeEnum::SingleChoice)
                            @foreach($this->form->currentQuestionOptions as $option)
                                <x-input-choice
                                    name="form.questionOptionSelectedId"
                                    type="radio"
                                    :value="$option->id"
                                    :label="$option->title"
                                    :checked="$this->form->questionOptionSelectedId"
                                    class="rounded-full"
                                />
                            @endforeach
                            <x-input-error for="form.questionOptionSelectedId" />
                        @endif
                        @if($this->form->currentQuestion->type === \App\Enums\Survey\SurveyQuestionTypeEnum::MultipleChoice->value)
                            @foreach($this->form->currentQuestionOptions as $option)
                                <x-input-choice
                                    name="form.questionOptionSelectedIds"
                                    type="checkbox"
                                    :value="$option->id"
                                    :label="$option->title"
                                    :checked="$this->form->questionOptionSelectedIds"
                                />
                            @endforeach
                        @endif
                    </div>
                    <div class="flex gap-4 col-span-full mt-4">
                        {{--<x-button-link href="#" wire:click="previousQuestion()"
                                       styleBtn="secondary">{{ __('Назад') }}</x-button-link>--}}
                        <x-button target="nextQuestion()">
                            {{ __('Далее') }}
                        </x-button>
                    </div>
                </form>
            @endif
            @if($this->form->isSurveyComplete)
                {{ __('Ученик :grade:letter класса :surname :name :patronymic успешно прошел тест', $this->form->studentData) }}
            @endif
            @if(!$this->form->isSurveyStarted)
                {{ __('Ученик :grade:letter класса :surname :name :patronymic ожидайте старта тестирования', $this->form->studentData) }}
            @endif
        </div>
    </div>
</div>
