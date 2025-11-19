<div>
    <form wire:submit="register">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-label for="surname" value="{{ __('Фамилия') }}" required />
                <x-input class="block mt-2 mb-2 w-full" wire:model="surname" autofocus />
                <x-input-error for="surname" />
            </div>
            <div>
                <x-label for="name" value="{{ __('Имя') }}" required />
                <x-input class="block mt-2 mb-2 w-full" wire:model="name" />
                <x-input-error for="name" />
            </div>
            <div class="col-span-full">
                <x-label for="patronymic" value="{{ __('Отчество') }}" required />
                <x-input class="block mt-2 mb-2 w-full" wire:model="patronymic" />
                <x-input-error for="patronymic" />
            </div>
            <div class="col-span-full">
                <x-label for="email" value="{{ __('Эл. почта') }}" required />
                <x-input type="email" class="block mt-2 mb-2 w-full" wire:model="email" />
                <x-input-error for="email" />
            </div>
            <div>
                <x-label for="password" value="{{ __('Пароль') }}" required />
                <x-input type="password" class="block mt-2 mb-2 w-full" wire:model="password" />
                <x-input-error for="password" />
            </div>
            <div>
                <x-label for="password_confirmation" value="{{ __('Повторите пароль') }}" required />
                <x-input type="password" class="block mt-2 mb-2 w-full" wire:model="password_confirmation" />
                <x-input-error for="password_confirmation" />
            </div>
            <div class="col-span-full">
                <x-label for="role" value="{{ __('Роль') }}" required />
                <x-select2 name="role_selected" options="roles" live />
                <x-input-error for="role_selected" />
            </div>
            @if($this->organizations)
               <div class="col-span-full">
                    <x-label for="organization" value="{{ __('БИН организации') }}" required />
                    <x-select2 name="organization_selected_id" options="organizations" watch />
                    <x-input-error for="organization_selected_id" />
                </div>
                @if($this->classrooms)
                    <div class="col-span-full">
                        <x-label for="classrooms" value="{{ __('Класс') }}" required />
                        <x-select2 name="classroom_selected_id" options="classrooms" watch />
                        <x-input-error for="classroom_selected_id" />
                    </div>
                @endif
            @endif
            @if($this->regions)
               <div class="col-span-full">
                    <x-label for="region" value="{{ __('Регион') }}" required />
                    <x-select2 name="region_selected_id" options="regions" live />
                    <x-input-error for="region_selected_id" />
                </div>
            @endif
            @if($this->districts)
               <div class="col-span-full">
                    <x-label for="district" value="{{ __('Район') }}" required />
                    <x-select2 name="district_selected_id" options="districts" watch />
                    <x-input-error for="district_selected_id" />
                </div>
            @endif

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="col-span-full">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-input type="checkbox" wire:model="terms" />
                            <div class="ms-2 block w-full">
                                {!! __('Согласен с :terms_of_service', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('text', ['slug' => 'terms']).'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('условиями обработки персональных данных').'</a>',
                                ]) !!}
                            </div>
                        </div>
                        <x-input-error for="terms" class="block w-full" />
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-5 col-span-full">
                <a class="text-sm text-primary hover:text-primary-light rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('login') }}" wire:navigate>
                    {{ __('Зарегистрированы?') }}
                </a>
                <x-button class="ms-4" wire:target="register">
                    <span wire:loading.remove>{{ __('Регистрация') }}</span>
                    <span wire:loading>{{ __('Загрузка...') }}</span>
                </x-button>
            </div>
        </div>
    </form>

</div>
