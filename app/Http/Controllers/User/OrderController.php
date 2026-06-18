<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Rental;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create(Request $request, Property $property)
    {
        abort_if(!$property->isAvailable() || $property->sisa_luas <= 0, 404);

        $luas = min(max((float) $request->query('luas', 1), 1), (float) $property->sisa_luas);
        $period = in_array($request->query('period'), ['harian', 'bulanan', 'tahunan'], true)
            ? $request->query('period')
            : 'bulanan';
        
        $baseAmount = $this->calculateBaseAmount($property, $luas, $period);
        $tax = (int) round($baseAmount * 0.11);
        $amount = $baseAmount + $tax;

        return view('user.orders.create', compact('property', 'luas', 'period', 'baseAmount', 'tax', 'amount'));
    }

    public function store(Request $request, Property $property, MidtransService $midtrans)
    {
        abort_if(!$property->isAvailable() || $property->sisa_luas <= 0, 404);

        $validated = $request->validate([
            'luas_sewa' => ['required', 'numeric', 'min:1', 'max:' . $property->sisa_luas],
            'period' => ['required', 'in:harian,bulanan,tahunan'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'agreement' => ['accepted'],
            'catatan' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        $startDate = Carbon::parse($validated['tanggal_mulai']);
        $endDate = $this->endDate($startDate, $validated['period']);
        $amount = $this->calculateAmount($property, (float) $validated['luas_sewa'], $validated['period']);

        if (!$midtrans->isConfigured()) {
            return back()
                ->withInput()
                ->withErrors(['payment' => 'Konfigurasi Midtrans Sandbox belum lengkap. Isi MIDTRANS_CLIENT_KEY dan MIDTRANS_SERVER_KEY di file .env, lalu jalankan php artisan config:clear.']);
        }

        $rental = Rental::create([
            'nama_penyewa' => $user->name,
            'no_telepon' => null,
            'email_penyewa' => $user->email,
            'property_id' => $property->id,
            'luas_sewa' => $validated['luas_sewa'],
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'status_sewa' => 'aktif',
            'catatan' => trim(($validated['catatan'] ?? '') . "\nPeriode order: " . $validated['period']),
        ]);

        $property->syncStatus();

        $merchantOrderId = 'ORDER-' . $rental->id . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
        $productDetails = 'Order sewa ' . $property->nama_properti;

        $response = $midtrans->createSnapTransaction([
            'transaction_details' => [
                'order_id' => $merchantOrderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'RENTAL-' . $rental->id,
                    'name' => $productDetails,
                    'price' => $amount,
                    'quantity' => 1,
                ],
            ],
            'callbacks' => [
                'finish' => route('user.dashboard'),
            ],
        ]);

        if (!$response->successful()) {
            $rental->delete();
            $property->syncStatus();

            return back()
                ->withInput()
                ->withErrors(['payment' => 'Gagal membuat transaksi Midtrans: ' . $response->body()]);
        }

        $result = $response->json();
        if (empty($result['redirect_url'])) {
            $rental->delete();
            $property->syncStatus();

            return back()
                ->withInput()
                ->withErrors(['payment' => 'Midtrans tidak mengembalikan redirect URL.']);
        }

        $rental->payment()->create([
            'merchant_order_id' => $merchantOrderId,
            'reference' => $result['token'] ?? null,
            'payment_method' => 'midtrans_snap',
            'amount' => $amount,
            'status' => 'pending',
            'payment_url' => $result['redirect_url'],
            'raw_response' => $result,
        ]);

        return redirect()->away($result['redirect_url']);
    }

    private function calculateBaseAmount(Property $property, float $luas, string $period): int
    {
        $pricePerMeter = match ($period) {
            'harian' => $property->harga_sewa / 30,
            'tahunan' => $property->harga_sewa * 12,
            default => $property->harga_sewa,
        };

        return (int) ceil($pricePerMeter * $luas);
    }

    private function calculateAmount(Property $property, float $luas, string $period): int
    {
        $base = $this->calculateBaseAmount($property, $luas, $period);
        return (int) round($base * 1.11);
    }

    private function endDate(Carbon $startDate, string $period): Carbon
    {
        return match ($period) {
            'harian' => $startDate->copy()->addDay(),
            'tahunan' => $startDate->copy()->addYear(),
            default => $startDate->copy()->addMonth(),
        };
    }

}
