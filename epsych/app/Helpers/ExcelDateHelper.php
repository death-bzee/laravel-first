<?php

namespace App\Helpers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelDateHelper
{
    /**
     * Нормализовать дату из Excel в формат Y-m-d.
     *
     * Поддержка:
     * - DateTimeInterface
     * - Excel serial (число или строка "45102" / "45102.0")
     * - "19.03.2014", "19-03-2014", "2014-03-19", "2014/03/19"
     * - "19.03.2014 00:00" (игнор времени)
     * - "3/19/2014" (US mm/dd/YYYY)
     */
    public static function normalize(mixed $raw): ?string
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        // 1) Объект даты
        if ($raw instanceof \DateTimeInterface) {
            return Carbon::instance($raw)->format('Y-m-d');
        }

        // 2) Excel serial (число или числовая строка)
        // Сначала пробуем как "голое число": это самый частый кейс
        if (is_int($raw) || is_float($raw) || (is_string($raw) && self::looksLikePureNumber($raw))) {
            try {
                $dt = Date::excelToDateTimeObject((float) $raw);

                return Carbon::instance($dt)->format('Y-m-d');
            } catch (\Throwable) {
                // пойдём дальше разбирать как строку
            }
        }

        // 3) Строки
        if (is_string($raw)) {
            $val = self::stripInvisibles(trim($raw));

            // Срежем время, если есть (например "19.03.2014 0:00:00")
            $valNoTime = preg_replace('/\s+\d{1,2}:\d{2}(:\d{2})?$/u', '', $val);

            // dd.mm.yyyy или dd-mm-yyyy
            if (preg_match('/^(\d{1,2})[.\-](\d{1,2})[.\-](\d{4})$/', $valNoTime, $m)) {
                return self::safeDate('d.m.Y', sprintf('%02d.%02d.%04d', $m[1], $m[2], $m[3]));
            }

            // yyyy-mm-dd или yyyy/mm/dd
            if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $valNoTime, $m)) {
                // Интерпретируем как Y-m-d (однозначный формат)
                $ymd = sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
                try {
                    return Carbon::createFromFormat('Y-m-d', $ymd)->format('Y-m-d');
                } catch (\Throwable) {
                    return null;
                }
            }

            // m/d/Y или d/m/Y — здесь добавим эвристику:
            // если вторая часть > 12, значит точно m/d/Y (пример: 3/19/2014)
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $valNoTime, $m)) {
                [$all, $a, $b, $y] = $m;

                if ((int) $b > 12) {
                    // mm/dd/YYYY (пример из твоих логов: 3/19/2014)
                    $mdy = sprintf('%02d/%02d/%04d', $a, $b, $y);

                    return self::safeDate('m/d/Y', $mdy);
                }

                // Если оба <= 12 — неоднозначно. По умолчанию считаем это d/m/Y,
                // т.к. в наших файлах чаще европейский формат. При необходимости поменяем.
                $dmy = sprintf('%02d/%02d/%04d', $a, $b, $y);
                $res = self::safeDate('d/m/Y', $dmy);
                if ($res === null) {
                    // fallback как m/d/Y
                    $res = self::safeDate('m/d/Y', $dmy);
                }

                return $res;
            }

            // Иногда дата-м serial приходит строкой "45102" или "45102.0"
            if (self::looksLikePureNumber($valNoTime)) {
                try {
                    $dt = Date::excelToDateTimeObject((float) $valNoTime);

                    return Carbon::instance($dt)->format('Y-m-d');
                } catch (\Throwable) {
                    // игнор
                }
            }
        }

        return null;
    }

    private static function safeDate(string $format, string $value): ?string
    {
        try {
            $dt = Carbon::createFromFormat($format, $value);
            // строгое соответствие формату
            if ($dt && $dt->format($format) === $value) {
                return $dt->format('Y-m-d');
            }
        } catch (\Throwable) {
        }

        return null;
    }

    private static function looksLikePureNumber(string $s): bool
    {
        // только цифры и один возможный десятичный разделитель
        return (bool) preg_match('/^\d+(?:[.,]\d+)?$/', $s);
    }

    /**
     * Убираем невидимые символы (NBSP, zero-width, RTL marks и т.п.)
     */
    private static function stripInvisibles(string $s): string
    {
        $s = preg_replace('/[\x{00A0}\x{202F}\x{2009}\x{200B}\x{200E}\x{200F}]/u', ' ', $s);
        $s = preg_replace('/\s+/u', ' ', $s);

        return trim($s);
    }
}
