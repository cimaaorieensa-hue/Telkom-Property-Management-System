<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Setting;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display a listing of the user's rentals.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Rental::with(['property', 'payment'])
            ->where('email_penyewa', $user->email);

        // Search by property name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('property', function ($q) use ($search) {
                $q->where('nama_properti', 'like', "%{$search}%");
            });
        }

        // Filter by rental status
        if ($request->filled('status_sewa')) {
            $query->where('status_sewa', $request->status_sewa);
        }

        $rentals = $query->latest()->paginate(10)->withQueryString();

        return view('user.rentals.index', compact('rentals'));
    }

    /**
     * Display the specified user's rental.
     */
    public function show(Rental $rental)
    {
        $user = auth()->user();

        // Security: Abort if the rental does not belong to the authenticated user
        abort_if($rental->email_penyewa !== $user->email, 403, 'Anda tidak memiliki akses ke data penyewaan ini.');

        $rental->load(['property', 'payment']);
        $setting = Setting::current();

        return view('user.rentals.show', compact('rental', 'setting'));
    }
}
