<?php

namespace App\Contracts;

interface ImageServiceContract
{
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
    public function resizeAndSaveImage(mixed $image, string $folder, int $width): string;
}
