<?php

namespace App\Services;

use App\Contracts\DocumentServiceContract;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\Document;

class DocumentService implements DocumentServiceContract
{
    /**
     * Сохраняет загруженные документы на диск и создает записи в базе данных.
     *
     * Метод принимает массив загруженных файлов, сохраняет их на диск в указанной
     * директории, а затем создает соответствующие записи в базе данных с информацией
     * о каждом файле. Связь файла с определенной группой документов и сущностью
     * указывается через передаваемые параметры.
     *
     * @param array $files Массив загруженных файлов (объекты TemporaryUploadedFile).
     * @param int $documentGroupId Идентификатор группы документов.
     * @param int $documentableId Идентификатор связанной сущности (например, пользователя, поста).
     * @param string $documentableType Тип связанной сущности (например, App\Models\User).
     * @return void
     */
    public function saveDocuments(array $files, int $documentGroupId, int $documentableId, string $documentableType): void
    {
        foreach ($files as $file) {
            if ($file instanceof TemporaryUploadedFile) {
                // Сохранение файла на диске
                $filePath = $file->store('documents', 'public');

                // Создание записи о документе в базе данных
                Document::create([
                    'file_path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_extension' => $file->getClientOriginalExtension(),
                    'document_group_id' => $documentGroupId,
                    'documentable_id' => $documentableId,
                    'documentable_type' => $documentableType,
                ]);
            }
        }
    }

    /**
     * Удаляет документ из массива документов и, если он объект, удаляет его из базы данных.
     *
     * Метод принимает ссылку на массив документов, идентификатор группы документов и индекс
     * документа в этой группе. Если документ существует, он удаляется из массива, а также
     * из базы данных, если он является объектом (записью в модели).
     *
     * @param array &$documents Ссылка на массив документов.
     * @param int $groupId Идентификатор группы документов.
     * @param int $index Индекс документа в массиве.
     * @return void
     */
    public function deleteDocumentArray(array &$documents, int $groupId, int $index): void
    {
        if (isset($documents[$groupId][$index])) {
            $document = $documents[$groupId][$index];
            if (is_object($document)) {
                $document->delete();
            }
            unset($documents[$groupId][$index]);
        }
    }

    /**
     * Удаляет документ по его идентификатору.
     *
     * Метод находит документ по переданному идентификатору и удаляет его из базы данных.
     * Если документ не найден, будет выброшено исключение ModelNotFoundException.
     *
     * @param int $documentId Идентификатор документа.
     * @return void
     */
    public function deleteDocument(int $documentId): void
    {
        $document = Document::findOrFail($documentId);
        $document->delete();
    }
}
