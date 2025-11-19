<?php

namespace App\Traits\Document;

use App\Contracts\DocumentServiceContract;
use Illuminate\Database\Eloquent\Model;

trait HasTmpDocuments
{
    /**
     * * ID группы документов, данные ИД добавляются в админке Группы документов
     * @param $groupId
     *
     * Индекс документа, в группе может быть N кол-во
     * @param $index
     *
     * @return void
     */
    public function deleteTmpDocument($groupId, $index): void
    {
        $documentService = app(DocumentServiceContract::class);
        $documentService->deleteDocumentArray($this->form->tmpDocuments, $groupId, $index);
    }

    protected function saveTmpDocuments(array $tmpDocuments, int $documentType, int $modelId, Model $model): array
    {
        if (!empty($tmpDocuments)) {
            $documentService = app(DocumentServiceContract::class);
            $documentService->saveDocuments($tmpDocuments[$documentType], $documentType, $modelId, get_class($model));
        }

        return [];
    }

}
