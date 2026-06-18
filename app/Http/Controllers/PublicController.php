<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Halaman Beranda.
     */
    public function index()
    {
        $setting = Setting::current();

        $featuredProperties = Property::where('status', 'tersedia')
            ->latest()
            ->take(6)
            ->get();

        $totalProperti = Property::count();
        $totalTersedia = Property::where('status', 'tersedia')->count();
        $totalDisewa = Property::where('status', 'disewa')->count();

        return view('public.beranda', compact(
            'setting',
            'featuredProperties',
            'totalProperti',
            'totalTersedia',
            'totalDisewa',
        ));
    }

    /**
     * Halaman Daftar Properti.
     */
    public function properties(Request $request)
    {
        $setting = Setting::current();

        $query = Property::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_properti', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['tersedia', 'disewa'])) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'harga_rendah':
                $query->orderByRaw('harga_sewa * luas ASC');
                break;
            case 'harga_tinggi':
                $query->orderByRaw('harga_sewa * luas DESC');
                break;
            case 'luas_terbesar':
                $query->orderBy('luas', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $properties = $query->paginate(9)->withQueryString();

        return view('public.properties', compact('setting', 'properties'));
    }

    /**
     * Halaman Detail Properti.
     */
    public function propertyDetail(Property $property)
    {
        $setting = Setting::current();

        $property->load('galleries');

        $relatedProperties = Property::where('id', '!=', $property->id)
            ->where('status', 'tersedia')
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('public.property-detail', compact('setting', 'property', 'relatedProperties'));
    }
}
