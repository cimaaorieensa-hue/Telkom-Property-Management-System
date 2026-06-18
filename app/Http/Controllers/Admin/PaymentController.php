<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request, Rental $rental, DuitkuService $duitku)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:10',
        ]);

        $rental->load(['property', 'payment']);

        if ($rental->payment?->status === 'paid') {
            return back()->with('error', 'Pembayaran untuk penyewaan ini sudah lunas.');
        }

        if ($rental->payment?->status === 'pending' && $rental->payment->payment_url) {
            return redirect()->away($rental->payment->payment_url);
        }

        $amount = (int) round($rental->total_tagihan);
        if ($amount <= 0) {
            return back()->with('error', 'Nominal pembayaran tidak valid.');
        }

        if (!$duitku->isConfigured()) {
            return back()->with('error', 'Konfigurasi Duitku Sandbox belum lengkap. Isi DUITKU_MERCHANT_CODE dengan Merchant Code asli dari dashboard Duitku, lalu jalankan php artisan config:clear.');
        }

        $merchantOrderId = 'RENTAL-' . $rental->id . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
        $productDetails = 'Pembayaran sewa ' . $rental->property->nama_properti;

        $response = $duitku->createTransaction([
            'paymentAmount' => $amount,
            'paymentMethod' => $validated['payment_method'],
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => '',
            'merchantUserInfo' => '',
            'customerVaName' => $rental->nama_penyewa,
            'email' => $rental->email_penyewa ?: 'customer@example.com',
            'phoneNumber' => $rental->no_telepon ?: '',
            'callbackUrl' => config('duitku.callback_url'),
            'returnUrl' => config('duitku.return_url'),
            'expiryPeriod' => 60,
            'itemDetails' => [
                [
                    'name' => $productDetails,
                    'price' => $amount,
                    'quantity' => 1,
                ],
            ],
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'Gagal membuat transaksi Duitku: ' . $response->body());
        }

        $result = $response->json();
        if (empty($result['paymentUrl'])) {
            return back()->with('error', 'Duitku tidak mengembalikan payment URL.');
        }

        $rental->payment()->updateOrCreate(
            ['rental_id' => $rental->id],
            [
                'merchant_order_id' => $merchantOrderId,
                'reference' => $result['reference'] ?? null,
                'payment_method' => $validated['payment_method'],
                'amount' => $amount,
                'status' => 'pending',
                'payment_url' => $result['paymentUrl'],
                'raw_response' => $result,
                'paid_at' => null,
            ]
        );

        return redirect()->away($result['paymentUrl']);
    }
}
