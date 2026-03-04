<?php

namespace App\Traits;

trait WithToasts
{
    public function toastExito(string $message): void
    {
        $this->dispatch('toast', type: 'success', message: $message);
    }

    public function toastInfo(string $message): void
    {
        $this->dispatch('toast', type: 'info', message: $message);
    }

    public function toastError(string $message): void
    {
        $this->dispatch('toast', type: 'error', message: $message);
    }

    public function toastWarning(string $message): void
    {
        $this->dispatch('toast', type: 'warning', message: $message);
    }
}
