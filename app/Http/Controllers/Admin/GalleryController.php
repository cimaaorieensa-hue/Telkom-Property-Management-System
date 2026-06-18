<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of properties to manage their galleries.
     */
    public function index(Request $request)
    {
        $query = Property::withCount('galleries');

        if ($request->filled('search')) {
            $query->where('nama_properti', 'like', "%{$request->search}%");
        }

        $properties = $query->latest()->paginate(10)->withQueryString();

        return view('admin.galleries.index', compact('properties'));
    }

    /**
     * Show the gallery for a specific property.
     */
    public function show(Property $property)
    {
        $property->load('galleries');
        return view('admin.galleries.show', compact('property'));
    }

    /**
     * Store multiple newly created gallery images in storage.
     */
    public function store(Request $request, Property $property)
    {
        try {
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'max:10240',
            ]);

            $uploadedCount = 0;

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('galleries/' . $property->id, 'public');
                    
                    $property->galleries()->create([
                        'image_path' => $path,
                    ]);
                    $uploadedCount++;
                }
            }

            return back()->with('success', "Berhasil menambahkan $uploadedCount foto ke galeri.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified gallery image from storage.
     */
    public function destroy(Gallery $gallery)
    {
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}
