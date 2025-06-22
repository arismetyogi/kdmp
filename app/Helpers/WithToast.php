<?php

namespace App\Helpers;

trait WithToast
{
    public function toast(
        string $message,
        string $type = 'default',
        ?string $description = null,
        string $position = 'top-center',
        ?string $html = null
    ): void {
        $payload = [
            'type' => $type,
            'message' => $message,
            'description' => $description,
            'position' => $position,
        ];

        if (! empty($html)) {
            $payload['html'] = $html;
        }

        $this->dispatch('toast-show', $payload);
    }
}
