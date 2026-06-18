<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Rental;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard manager (monitoring read-only).
     */
    public function index()
    {
        $totalProperti = Property::count();
        $propertiTersedia = Property::where('status', 'tersedia')->count();
        $propertiDisewa = Property::where('status', 'disewa')->count();
        $totalPenyewaan = Rental::where('status_sewa', 'aktif')->count();

        $recentRentals = Rental::with('property')
            ->latest()
            ->take(10)
            ->get();

        return view('manager.dashboard', compact(
            'totalProperti',
            'propertiTersedia',
            'propertiDisewa',
            'totalPenyewaan',
            'recentRentals',
        ));
    }
}
