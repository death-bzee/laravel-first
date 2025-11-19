<?php

namespace App\Helpers;

final class ExcelIinHelper
{
    /**
     * Нормализует ИИН до 12-значной строки.
     *
     * Поддержка грязных входов:
     * - "140319605321.00"  -> "140319605321"
     * - "1.40319605321E+11" (excel scientific) -> "140319605321"
     * - "  1403 1960 5321 " -> "140319605321"
     * - любые символы вокруг — вытащит первые 12 подряд идущих цифр
     *
     * Возвращает null, если 12 подряд цифр не найдено.
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

        // Быстрый кейс: ровно 12 цифр
        if (preg_match('/^\d{12}$/', $s)) {
            return $s;
        }

        // Если вид "XXXXXXXXXXXX.00" — отсекаем дробную часть
        if (preg_match('/^(\d{12})[.,]0+$/', $s, $m)) {
            return $m[1];
        }

        // Scientific notation типа "1.40319605321E+11"
        // Эвристика: вытащим первую «длинную» подряд идущую пачку цифр,
        // а если их больше 12 — возьмём первые 12 (в Excel ИИН обычно без лидирующих нулей)
        if (stripos($s, 'e+') !== false || stripos($s, 'e-') !== false) {
            // уберём всё, кроме цифр, и возьмём первые 12 подряд
            if (preg_match('/(\d{12})/', preg_replace('/\D+/', '', $s), $m)) {
                return $m[1];
            }
        }

        // Общий случай: выдернуть первые 12 подряд идущих цифр из строки
        if (preg_match('/(\d{12})/', $s, $m)) {
            return $m[1];
        }

        // Если подряд 12 нет, попробуем собрать все цифры и проверить
        $digits = preg_replace('/\D+/', '', $s) ?? '';
        if (strlen($digits) === 12) {
            return $digits;
        }

        return null;
    }
}
