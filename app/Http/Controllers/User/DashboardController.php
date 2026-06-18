<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Rental;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $rentals = Rental::with(['property', 'payment'])
            ->where('email_penyewa', $user->email)
            ->latest()
            ->take(5)
            ->get();

        $availableProperties = Property::where('status', 'tersedia')
            ->latest()
            ->take(6)
            ->get();

        return view('user.dashboard', compact('user', 'rentals', 'availableProperties'));
    }
}
