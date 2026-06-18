<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Rental;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin.
     */
    public function index()
    {
        $totalProperti = Property::count();
        $propertiTersedia = Property::where('status', 'tersedia')->count();
        $propertiDisewa = Property::where('status', 'disewa')->count();
        $totalPenyewaan = Rental::where('status_sewa', 'aktif')->count();

        $recentProperties = Property::latest()->take(5)->get();
        $recentRentals = Rental::with('property')
            ->where('status_sewa', 'aktif')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProperti',
            'propertiTersedia',
            'propertiDisewa',
            'totalPenyewaan',
            'recentProperties',
            'recentRentals',
        ));
    }
}
