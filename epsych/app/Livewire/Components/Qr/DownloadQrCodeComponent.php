<?php

namespace App\Livewire\Components\Qr;

use App\Contracts\QrCodeServiceContract;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadQrCodeComponent extends Component
{
    public Model $model;

    public string $uri;

    public function mount(Model $model, string $uri): void
    {
        $this->model = $model;
        $this->uri = $uri;
    }

    public function download(): StreamedResponse
    {
        $service = app(QrCodeServiceContract::class);

        $url = config('app.url').$this->uri.$this->model->id;

        $qrCode = $service->generateQrCodeRecord($this->model, ['url' => $url]);

        $qrCodeImage = $service->generateQrCodeImage($qrCode->meta['url']);

        $fileName = class_basename($this->model).'-qr-code-'.$this->model->getKey().'.png';

        return response()->stream(function () use ($qrCodeImage) {
            echo $qrCodeImage;
        }, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            'Pragma' => 'public',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Length' => strlen($qrCodeImage),
        ]);
    }

    public function render(): View
    {
        return view('livewire.components.qr.download-qr-code-component');
    }
}
