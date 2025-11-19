<div>
    <div class="mb-5 text-sm font-medium">
        {{ __('Забыли пароль? Не проблема. Просто сообщите нам свой адрес электронной почты, и мы отправим вам ссылку для сброса пароля, которая позволит вам выбрать новый.') }}
	</div>
	<form wire:submit="sendResetLink">
		@session('status')
		<div class="mb-5 font-medium text-sm text-green-600">
			{{ $value }}
		</div>
		@endsession
		<div class="block">
			<x-label for="email" value="{{ __('Email') }}" />
			<x-input type="email" class="block mt-2 mb-2 w-full" wire:model="email" autofocus />
			<x-input-error for="email" />
		</div>
		<div class="flex items-center justify-end mt-4">
			<a class="text-sm text-primary hover:text-primary-light rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
			   href="{{ route('login') }}" wire:navigate>
				{{ __('Назад ко входу') }}
			</a>
			<x-button wire:loading.attr="disabled" class="ms-4">
				<span wire:loading.remove>{{ __('Отправить ссылку для сброса') }}</span>
				<span wire:loading>{{ __('Загрузка...') }}</span>
            </x-button>
        </div>
    </form>
</div>
