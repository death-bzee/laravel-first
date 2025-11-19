<div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if ($status === 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <div>
            <x-button wire:click="sendVerification" wire:loading.attr="disabled">
                 <span wire:loading.remove>{{ __('Resend Verification Email') }}</span>
                <span wire:loading>{{ __('Processing...') }}
            </x-button>
        </div>

        <div>
            <a
                wire:navigate
                href="{{ route('profile.show') }}"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                {{ __('Edit Profile') }}
            </a>
            <div class="inline">
                <a href="#" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2"
                      wire:click="logout">
                {{ __('Log Out') }}
                </a>
            </div>

        </div>
    </div>
</div>
