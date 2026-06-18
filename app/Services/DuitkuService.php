<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class DuitkuService
{
    public function getPaymentMethods(int $amount): Response
    {
        $datetime = now()->format('Y-m-d H:i:s');

        return Http::asJson()->post($this->url('/merchant/paymentmethod/getpaymentmethod'), [
            'merchantcode' => $this->merchantCode(),
            'amount' => $amount,
            'datetime' => $datetime,
            'signature' => $this->paymentMethodSignature($amount, $datetime),
        ]);
    }

    public function createTransaction(array $payload): Response
    {
        $payload['merchantCode'] = $this->merchantCode();
        $payload['signature'] = $this->transactionSignature(
            $payload['merchantOrderId'],
            (int) $payload['paymentAmount']
        );

        return Http::asJson()->post($this->url('/merchant/v2/inquiry'), $payload);
    }

    public function checkTransaction(string $merchantOrderId): Response
    {
        return Http::asJson()->post($this->url('/merchant/transactionStatus'), [
            'merchantCode' => $this->merchantCode(),
            'merchantOrderId' => $merchantOrderId,
            'signature' => $this->checkTransactionSignature($merchantOrderId),
        ]);
    }

    public function transactionSignature(string $merchantOrderId, int $paymentAmount): string
    {
        return hash_hmac(
            'sha256',
            $this->merchantCode() . $merchantOrderId . $paymentAmount,
            $this->apiKey()
        );
    }

    public function paymentMethodSignature(int $amount, string $datetime): string
    {
        return hash_hmac(
            'sha256',
            $this->merchantCode() . $amount . $datetime,
            $this->apiKey()
        );
    }

    public function callbackSignature(string $merchantCode, int $amount, string $merchantOrderId): string
    {
        return hash_hmac(
            'sha256',
            $merchantCode . $amount . $merchantOrderId,
            $this->apiKey()
        );
    }

    public function checkTransactionSignature(string $merchantOrderId): string
    {
        return hash_hmac(
            'sha256',
            $this->merchantCode() . $merchantOrderId,
            $this->apiKey()
        );
    }

    public function isValidCallback(array $payload): bool
    {
        if (
            empty($payload['merchantCode']) ||
            empty($payload['amount']) ||
            empty($payload['merchantOrderId']) ||
            empty($payload['signature'])
        ) {
            return false;
        }

        $signature = $this->callbackSignature(
            $payload['merchantCode'],
            (int) $payload['amount'],
            $payload['merchantOrderId']
        );

        return hash_equals($signature, $payload['signature']);
    }

    public function merchantCode(): string
    {
        return (string) config('duitku.merchant_code');
    }

    public function isConfigured(): bool
    {
        return $this->merchantCode() !== ''
            && $this->merchantCode() !== 'DXXXX'
            && $this->apiKey() !== '';
    }

    public function apiKey(): string
    {
        return (string) config('duitku.api_key');
    }

    private function url(string $path): string
    {
        return rtrim((string) config('duitku.base_url'), '/') . $path;
    }
}
