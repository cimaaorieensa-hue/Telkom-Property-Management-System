<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Rental;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Seed the properties table with sample data.
     */
    public function run(): void
    {
        // Buat 8 properti tersedia
        $availableProperties = Property::factory()
            ->count(8)
            ->available()
            ->create();

        // Buat 4 properti yang sedang disewa (beserta data rental aktif)
        $rentedProperties = Property::factory()
            ->count(4)
            ->rented()
            ->create();

        // Buat data penyewaan aktif untuk properti yang disewa
        foreach ($rentedProperties as $property) {
            Rental::factory()->create([
                'property_id' => $property->id,
                'status_sewa' => 'aktif',
            ]);
        }

        // Buat beberapa data penyewaan historis (selesai) untuk properti yang tersedia
        foreach ($availableProperties->take(4) as $property) {
            Rental::factory()->completed()->create([
                'property_id' => $property->id,
            ]);
        }
    }
}
