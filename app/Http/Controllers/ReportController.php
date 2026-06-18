<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Property;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan.
     */
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // 1. Distribusi Status Properti (Tersedia vs Disewa)
        $propertyStatus = Property::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $tersedia = $propertyStatus['tersedia'] ?? 0;
        $disewa = $propertyStatus['disewa'] ?? 0;

        // 2. Pendapatan Bulanan dari payment yang sudah lunas.
        $monthlyRevenue = array_fill(1, 12, 0);
        $monthlyRentalsCount = array_fill(1, 12, 0);

        $paidPaymentsThisYear = Payment::query()
            ->where('status', 'paid')
            ->whereYear('paid_at', $year)
            ->get();

        foreach ($paidPaymentsThisYear as $payment) {
            $month = Carbon::parse($payment->paid_at)->format('n');
            $monthlyRevenue[$month] += (float) $payment->amount;
        }

        $rentalsThisYear = Rental::query()
            ->whereYear('tanggal_mulai', $year)
            ->whereIn('status_sewa', ['aktif', 'selesai']) // menganggap yang selesai juga pernah bayar
            ->get();

        foreach ($rentalsThisYear as $rental) {
            $month = Carbon::parse($rental->tanggal_mulai)->format('n');
            $monthlyRentalsCount[$month]++;
        }

        // 3. Ringkasan
        $totalRevenueYear = array_sum($monthlyRevenue);
        $totalRentalsYear = array_sum($monthlyRentalsCount);
        $paidPaymentsCount = $paidPaymentsThisYear->count();

        return view('reports.index', compact(
            'year',
            'tersedia',
            'disewa',
            'monthlyRevenue',
            'monthlyRentalsCount',
            'totalRevenueYear',
            'totalRentalsYear',
            'paidPaymentsCount'
        ));
    }
}
