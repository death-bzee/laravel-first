<?php

namespace App\Livewire\Pages\Survey;

use App\Contracts\QrCodeServiceContract;
use App\Models\QrCode;
use App\Models\Survey\SurveyGroupAssignment;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.guest')]
class LoginShortCode extends Component
{
    public string $code = '';

    protected array $rules = [
        'code' => 'required|string|size:6',
    ];

    public function submit(): void
    {
        $this->validate([
            'code' => 'required|string|size:6',
        ]);

        $code = Str::of($this->code)->trim();

        // Поиск без учета регистра - пользователь может вводить как хочет
        $qrCode = QrCode::query()
            ->where('qr_codeable_type', SurveyGroupAssignment::class)
            ->whereRaw('LOWER(RIGHT(uuid, 6)) = LOWER(?)', [$code])
            ->first();

        if (!$qrCode) {
            $this->addError('code', 'Неверный код. Попробуйте ещё раз.');
            return;
        }

        // Получаем связанную модель через QR сервис
        $qrCodeService = app(QrCodeServiceContract::class);
        $assignment = $qrCodeService->getModelQrCode($qrCode->uuid);

        if (!$assignment || !($assignment instanceof SurveyGroupAssignment)) {
            $this->addError('code', 'Неверный код. Попробуйте ещё раз.');
            return;
        }

        session()->put('survey_group_id', $assignment->id);

        // переход к выбору студента
        $this->redirectRoute('student.login-qr-code');
    }

    public function render()
    {
        return view('livewire.pages.survey.login-short-code');
    }
}
