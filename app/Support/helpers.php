<?php

if (!function_exists('short_batch')) {
    /**
     * Shorten a batch code string like BATCH-20250613091331-xxxx-xxxx-xxxx-xxxxxxxxxxxx
     * to format: ...20250613091331...d01694e6cd4c
     */
    function short_batch(string $batch): string
    {
        return '...' . substr($batch, 6, 14) . '...' . substr($batch, -12);
    }
}


if (!function_exists('currency_format')) {
    function currency_format($amount, $locale = 'id-ID', $currency = 'IDR'): false|string|int
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
        return $formatter->formatCurrency($amount, $currency);
    }
}
