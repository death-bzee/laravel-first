<?php

namespace App\Contracts;

interface MaterialServiceContract
{
    public function processVideos(array $videos): array;
    public function processFiles(array $files): array;

}
