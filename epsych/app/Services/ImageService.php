<?php

namespace App\Services;

use App\Contracts\ImageServiceContract;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\WithFileUploads;

class ImageService implements ImageServiceContract
{
    use WithFileUploads;

    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Изменяет размер изображения и сохраняет его в указанную папку.
     *
     * Этот метод принимает изображение, изменяет его размер до указанной ширины,
     * кодирует его в формат JPG и сохраняет в заданной директории на диске.
     * Метод возвращает путь к сохраненному изображению.
     *
     * @param mixed $image Загруженный файл изображения (ожидается массив файлов).
     * @param string $folder Папка, в которую будет сохранено изображение.
     * @param int $width Ширина, до которой нужно изменить размер изображения.
     * @return string Путь к сохраненному изображению.
     */
    public function resizeAndSaveImage(mixed $image, string $folder, int $width): string
    {
        // Открытие изображения
        $img = $this->manager->read($image[0]->getRealPath());

        $extension = $image[0]->getClientOriginalExtension();

        // Изменение размера изображения
        $img->scale(width: $width);

        // Кодирование изображения
        $encoded = $img->toJpg();

        // Сохранение изображения на диск
        $fileName = md5_file($image[0]->getRealPath());
        $filePath = $folder . '/' . $fileName . '.' . $extension;
        Storage::disk('public')->put($filePath, (string)$encoded);

        return $filePath;
    }
}
