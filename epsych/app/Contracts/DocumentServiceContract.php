<?php

namespace App\Contracts;

interface DocumentServiceContract
{
    /**
     * Сохраняет загруженные документы на диск и создает записи в базе данных.
     *
     * @param array $files Массив загруженных файлов (объекты TemporaryUploadedFile).
     * @param int $documentGroupId Идентификатор группы документов.
     * @param int $documentableId Идентификатор связанной сущности (например, пользователя, поста).
     * @param string $documentableType Тип связанной сущности (например, App\Models\User).
     * @return void
     */
    public function saveDocuments(array $files, int $documentGroupId, int $documentableId, string $documentableType): void;

    /**
     * Удаляет документ из массива документов и, если он объект, удаляет его из базы данных.
     *
     * @param array &$documents Ссылка на массив документов.
     * @param int $groupId Идентификатор группы документов.
     * @param int $index Индекс документа в массиве.
     * @return void
     */
    public function deleteDocumentArray(array &$documents, int $groupId, int $index): void;

    /**
     * Удаляет документ по его идентификатору.
     *
     * @param int $documentId Идентификатор документа.
     * @return void
     */
    public function deleteDocument(int $documentId): void;
}
