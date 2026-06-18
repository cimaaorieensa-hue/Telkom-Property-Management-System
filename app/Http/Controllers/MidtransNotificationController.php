<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->all();

        if (!$this->isValidSignature($payload)) {
            abort(403, 'Invalid Midtrans notification signature.');
        }

        $payment = Payment::where('merchant_order_id', $payload['order_id'] ?? '')->firstOrFail();
        $status = $this->mapTransactionStatus(
            (string) ($payload['transaction_status'] ?? ''),
            (string) ($payload['fraud_status'] ?? '')
        );

        $updates = [
            'payment_method' => $payload['payment_type'] ?? $payment->payment_method,
            'status' => $status,
            'raw_response' => $payload,
        ];

        if ($status === 'paid') {
            $updates['paid_at'] = $payment->paid_at ?? now();
        }

        $payment->update($updates);

        return response('OK');
    }

    private function isValidSignature(array $payload): bool
    {
        if (
            empty($payload['order_id']) ||
            empty($payload['status_code']) ||
            empty($payload['gross_amount']) ||
            empty($payload['signature_key'])
        ) {
            return false;
        }

        $signature = hash(
            'sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            config('midtrans.server_key')
        );

        return hash_equals($signature, $payload['signature_key']);
    }

    private function mapTransactionStatus(string $transactionStatus, string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? 'pending' : 'paid';
        }

        return match ($transactionStatus) {
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'failure' => 'failed',
            'cancel' => 'cancelled',
            'expire' => 'expired',
            default => 'pending',
        };
    }
}
