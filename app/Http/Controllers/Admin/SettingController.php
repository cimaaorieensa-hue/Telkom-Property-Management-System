<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman form pengaturan.
     */
    public function index()
    {
        // Ambil data setting pertama (karena hanya ada 1 record konfigurasi)
        $setting = Setting::firstOrCreate(['id' => 1], [
            'nama_perusahaan' => 'Telkom Property',
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Simpan perubahan pengaturan.
     */
    public function update(Request $request)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'no_whatsapp' => 'nullable|string|max:50',
            'no_telepon' => 'nullable|string|max:50',
            'email_perusahaan' => 'nullable|email|max:255',
            'tentang' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            // Hapus logo lama
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            
            $path = $request->file('logo')->store('settings', 'public');
            $validated['logo'] = $path;
        }

        $setting->update($validated);

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
