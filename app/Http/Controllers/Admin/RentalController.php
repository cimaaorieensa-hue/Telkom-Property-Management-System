<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Rental;
use App\Services\DuitkuService;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Rental::with(['property', 'payment']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_penyewa', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%")
                  ->orWhere('email_penyewa', 'like', "%{$search}%")
                  ->orWhereHas('property', function ($qProp) use ($search) {
                      $qProp->where('nama_properti', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status_sewa')) {
            $query->where('status_sewa', $request->status_sewa);
        }

        $rentals = $query->latest()->paginate(10)->withQueryString();

        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only get properties that are not currently active in rental
        // or just list all available properties. For now, list all.
        $properties = Property::orderBy('nama_properti')->get();
        return view('admin.rentals.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $property = Property::findOrFail($request->property_id);
        $validated = $request->validate([
            'nama_penyewa' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'email_penyewa' => 'nullable|email|max:255',
            'property_id' => 'required|exists:properties,id',
            'luas_sewa' => 'required|numeric|min:1|max:' . $property->sisa_luas,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_sewa' => 'required|in:aktif,selesai,dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        $rental = Rental::create($validated);
        $rental->property->syncStatus();

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Data penyewaan berhasil ditambahkan.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Rental $rental, DuitkuService $duitku)
    {
        $rental->load(['property', 'payment']);
        $paymentMethods = $this->paymentMethodFallback();

        if (config('duitku.merchant_code') !== 'DXXXX') {
            try {
                $response = $duitku->getPaymentMethods((int) round($rental->total_pendapatan));

                if ($response->successful()) {
                    $paymentMethods = $response->json('paymentFee') ?: $paymentMethods;
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return view('admin.rentals.show', compact('rental', 'paymentMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        $properties = Property::orderBy('nama_properti')->get();
        return view('admin.rentals.edit', compact('rental', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        $property = Property::findOrFail($request->property_id);
        // max luas is sisa_luas + current rental's luas_sewa if they are keeping the same property
        $maxLuas = $request->property_id == $rental->property_id 
            ? $property->sisa_luas + $rental->luas_sewa 
            : $property->sisa_luas;

        $validated = $request->validate([
            'nama_penyewa' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'email_penyewa' => 'nullable|email|max:255',
            'property_id' => 'required|exists:properties,id',
            'luas_sewa' => 'required|numeric|min:1|max:' . $maxLuas,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_sewa' => 'required|in:aktif,selesai,dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        $oldPropertyId = $rental->property_id;
        $rental->update($validated);

        if ($oldPropertyId != $rental->property_id) {
            Property::find($oldPropertyId)->syncStatus();
        }
        $rental->property->syncStatus();

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Data penyewaan berhasil diperbarui.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'status_sewa' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $rental->update(['status_sewa' => $validated['status_sewa']]);
        $rental->property->syncStatus();

        return back()->with('success', 'Status sewa berhasil diubah menjadi ' . ucfirst($validated['status_sewa']) . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        $rental->delete();

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Data penyewaan berhasil dihapus.');
    }

    private function paymentMethodFallback(): array
    {
        return [
            ['paymentMethod' => 'BK', 'paymentName' => 'BCA Virtual Account'],
            ['paymentMethod' => 'M2', 'paymentName' => 'Mandiri Virtual Account'],
            ['paymentMethod' => 'I1', 'paymentName' => 'BNI Virtual Account'],
            ['paymentMethod' => 'BR', 'paymentName' => 'BRI Virtual Account'],
            ['paymentMethod' => 'NQ', 'paymentName' => 'QRIS'],
            ['paymentMethod' => 'SP', 'paymentName' => 'ShopeePay'],
        ];
    }
}
