<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function symbol(): string
    {
        return (string) config('site.currency.symbol', '$');
    }

    public static function startingFromLabel(): string
    {
        return (string) config('site.currency.starting_from', 'Starting from');
    }

    public static function formatAmount(float|string|null $amount = null, ?string $display = null): string
    {
        $value = $display ?? $amount;

        if ($value === null || $value === '') {
            return self::symbol().'0';
        }

        if (is_numeric($value)) {
            return self::symbol().number_format((float) $value, 0);
        }

        $clean = trim((string) $value);
        $clean = preg_replace('/^[₹$€£]\s*/u', '', $clean) ?? $clean;
        $clean = trim($clean);

        if ($clean === '') {
            return self::symbol().'0';
        }

        if (preg_match('/^[\d,]+(?:\.\d+)?$/', $clean)) {
            return self::symbol().$clean;
        }

        return self::symbol().$clean;
    }

    public static function startingFrom(float|string|null $amount = null, ?string $display = null): string
    {
        return self::startingFromLabel().' '.self::formatAmount($amount, $display);
    }

    public static function parseNumeric(float|string|null $amount = null, ?string $display = null): float
    {
        $value = $display ?? $amount;

        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return round((float) $value, 2);
        }

        $clean = trim((string) $value);
        $clean = preg_replace('/^[₹$€£]\s*/u', '', $clean) ?? $clean;
        $clean = str_replace(',', '', $clean);

        return is_numeric($clean) ? round((float) $clean, 2) : 0.0;
    }
}
