<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguageSwitcherComponent extends Component
{
    public string $currentLocale;
    public array $availableLocales;

    public function mount(): void
    {
        $this->currentLocale = App::getLocale();
        $this->availableLocales = LaravelLocalization::getSupportedLocales();
    }

    public function switchLanguage(string $locale): null
    {
        if (array_key_exists($locale, $this->availableLocales)) {
            LaravelLocalization::setLocale($locale);
            Session::put('locale', $locale);
            $this->currentLocale = $locale;
        }

        $redirectUrl = LaravelLocalization::getLocalizedURL($locale, url()->previous());

        return $this->redirect($redirectUrl);
    }


    public function render()
    {
        return view('livewire.components.language-switcher-component');
    }
}
