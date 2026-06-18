<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Property::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_properti', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $properties = $query->latest()->paginate(10)->withQueryString();

        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_properti' => 'required|string|max:255',
            'alamat' => 'required|string',
            'luas' => 'required|numeric|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,disewa',
            'deskripsi' => 'nullable|string',
            'link_google_maps' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('properties', 'public');
        }

        Property::create($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Data properti berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        return view('admin.properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'nama_properti' => 'required|string|max:255',
            'alamat' => 'required|string',
            'luas' => 'required|numeric|min:0',
            'harga_sewa' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,disewa',
            'deskripsi' => 'nullable|string',
            'link_google_maps' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($property->thumbnail) {
                Storage::disk('public')->delete($property->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('properties', 'public');
        }

        $property->update($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Data properti berhasil diperbarui.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Property $property)
    {
        $validated = $request->validate([
            'status' => 'required|in:tersedia,disewa',
        ]);

        $property->update(['status' => $validated['status']]);

        return back()->with('success', 'Status properti berhasil diubah menjadi ' . ucfirst($validated['status']) . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        // Delete thumbnail if exists
        if ($property->thumbnail) {
            Storage::disk('public')->delete($property->thumbnail);
        }

        // Gallery images will be deleted automatically due to cascade on delete in DB
        // But we should delete the actual files from storage
        foreach ($property->galleries as $gallery) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Data properti berhasil dihapus.');
    }
}
