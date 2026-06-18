<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the settings table with default company info.
     */
    public function run(): void
    {
        Setting::create([
            'nama_perusahaan' => 'Telkom Property',
            'alamat_perusahaan' => 'Jl. Japati No. 1, Bandung, Jawa Barat 40133',
            'no_whatsapp' => '081234567890',
            'no_telepon' => '022-1234567',
            'email_perusahaan' => 'info@telkomproperty.com',
            'tentang' => 'Telkom Property adalah unit pengelola aset properti milik Telkom Group yang menyediakan solusi properti komersial terbaik di berbagai lokasi strategis di Indonesia.',
        ]);
    }
}
