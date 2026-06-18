<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class MidtransService
{
    public function createSnapTransaction(array $payload): Response
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->serverKey() . ':'),
        ])->post($this->snapUrl(), $payload);
    }

    public function isConfigured(): bool
    {
        return $this->serverKey() !== ''
            && $this->clientKey() !== '';
    }

    public function serverKey(): string
    {
        return (string) config('midtrans.server_key');
    }

    public function clientKey(): string
    {
        return (string) config('midtrans.client_key');
    }

    private function snapUrl(): string
    {
        if (filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOL)) {
            return 'https://app.midtrans.com/snap/v1/transactions';
        }

        return 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }
}
