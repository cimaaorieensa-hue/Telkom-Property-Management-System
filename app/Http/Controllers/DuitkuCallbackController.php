<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DuitkuCallbackController extends Controller
{
    public function handle(Request $request, DuitkuService $duitku): Response
    {
        $payload = $request->all();

        if (!$duitku->isValidCallback($payload)) {
            abort(403, 'Invalid Duitku callback signature.');
        }

        $payment = Payment::where('merchant_order_id', $payload['merchantOrderId'])->firstOrFail();
        $status = $this->mapResultCode((string) ($payload['resultCode'] ?? ''));

        $updates = [
            'reference' => $payload['reference'] ?? $payment->reference,
            'payment_method' => $payload['paymentCode'] ?? $payment->payment_method,
            'status' => $status,
            'raw_response' => $payload,
        ];

        if ($status === 'paid') {
            $updates['paid_at'] = $payment->paid_at ?? now();
        }

        $payment->update($updates);

        return response('OK');
    }

    public function redirect(Request $request)
    {
        return redirect()
            ->route('admin.rentals.index')
            ->with('success', 'Pembayaran sedang diproses. Status akan diperbarui setelah callback Duitku diterima.');
    }

    private function mapResultCode(string $resultCode): string
    {
        return match ($resultCode) {
            '00' => 'paid',
            '01' => 'pending',
            '02' => 'cancelled',
            default => 'failed',
        };
    }
}
