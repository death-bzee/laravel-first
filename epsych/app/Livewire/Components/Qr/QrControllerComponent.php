<?php

namespace App\Livewire\Components\Qr;

use App\Actions\Qr\GetBullyingFormAction;
use App\Actions\Qr\LoginToSurveyAction;
use App\Contracts\Qr\QrCodeActionContract;
use App\Contracts\QrCodeServiceContract;
use App\Models\Organization;
use App\Models\Survey\SurveyGroupAssignment;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class QrControllerComponent extends Component
{
    public ?SurveyGroupAssignment $surveyGroupAssignment = null;

    public ?Organization $organization = null;

    protected array $handlers = [
        SurveyGroupAssignment::class => LoginToSurveyAction::class,
        Organization::class => GetBullyingFormAction::class,
    ];

    /**
     * @throws Exception
     */
    #[On('qr-code-scanned')]
    public function controller(string $uuidQrCode): void
    {
        /** @var Model|null $model */
        $model = app(QrCodeServiceContract::class)->getModelQrCode($uuidQrCode);

        if (!$model) {
            $this->addError('qr', __('QR-код не найден.'));

            return;
        }

        $handlerClass = $this->handlers[$model::class] ?? null;

        if (!$handlerClass || !is_subclass_of($handlerClass, QrCodeActionContract::class)) {
            $this->addError('qr', __('Обработчик для данной модели не найден.'));

            return;
        }

        $handler = app()->makeWith($handlerClass, ['component' => $this]);
        $handler->handle($model);
    }

    public function render(): View
    {
        return view('livewire.components.qr.qr-controller-component');
    }
}
