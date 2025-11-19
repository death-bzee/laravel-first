<?php

namespace App\Traits\Document;

use App\Contracts\DocumentServiceContract;

trait HasStoredDocuments
{
    /**
     * @param $id
     *
     * ID группы документов, данные ИД добавляются в админке Группы документов
     * @param $groupId
     *
     * Индекс документа, в группе может быть N кол-во
     * @param $index
     * @return void
     */
    public function deleteStoredDocument($id, $groupId, $index): void
    {
        $documentService = app(DocumentServiceContract::class);
        $documentService->deleteDocumentArray($this->form->storedDocuments, $groupId, $index);
        $documentService->deleteDocument($id);
    }
}
