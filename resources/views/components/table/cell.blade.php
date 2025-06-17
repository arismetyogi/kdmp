@props([
    'index' => false,
])
@php
    $slotValue = trim((string) $slot);
    $isNumeric = is_numeric($slotValue);
@endphp

<td {{ $attributes->class([
    "px-4 py-1 text-zinc-600 dark:text-zinc-50 text-nowrap",
    'text-end font-mono' => !$index && $isNumeric,
    ]) }}
>{{ !$index && is_numeric($slotValue) ? number_format((float) $slotValue) : $slot }}</td>
