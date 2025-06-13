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
