<?php

namespace App\Helpers;

final class ExcelPhoneHelper
{
    /**
     * Нормализует телефон в формат +77071112233.
     *
     * Поддержка:
     * - "8 707 111 22 33"  -> +77071112233
     * - "87071112233"      -> +77071112233
     * - "+7 (707) 111-22-33" -> +77071112233
     * - "7071112233"       -> +77071112233
     *
     * Возвращает null, если не удалось привести к 11-значному номеру.
     */
    public static function normalize(mixed $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $s = trim((string) $raw);

        if ($s === '') {
            return null;
        }

        // Убираем всё, кроме цифр
        $digits = preg_replace('/\D+/', '', $s) ?? '';

        if ($digits === '') {
            return null;
        }

        // Если начинается с 8 и длина 11 -> заменяем на 7
        if (strlen($digits) === 11 && str_starts_with($digits, '8')) {
            $digits = '7'.substr($digits, 1);
        }

        // Если начинается с 77 и длина 10 -> добавим 7 спереди
        if (strlen($digits) === 10 && str_starts_with($digits, '7')) {
            $digits = '7'.$digits;
        }

        // Проверяем финальный формат
        if (strlen($digits) === 11 && str_starts_with($digits, '7')) {
            return '+'.$digits;
        }

        return null;
    }
}
