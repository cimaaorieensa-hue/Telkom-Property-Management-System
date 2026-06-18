<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $propertyTypes = [
            'Gedung Perkantoran', 'Ruko', 'Gudang', 'Lahan Parkir',
            'Ruang Meeting', 'Co-Working Space', 'Apartemen', 'Tanah Kavling',
        ];

        $cities = [
            'Bandung', 'Jakarta Selatan', 'Jakarta Pusat', 'Surabaya',
            'Yogyakarta', 'Semarang', 'Medan', 'Makassar',
        ];

        $type = fake()->randomElement($propertyTypes);
        $city = fake()->randomElement($cities);

        return [
            'nama_properti' => $type . ' ' . fake()->lastName() . ' ' . fake()->randomNumber(2),
            'alamat' => 'Jl. ' . fake()->streetName() . ' No. ' . fake()->buildingNumber() . ', ' . $city,
            'luas' => fake()->randomFloat(2, 50, 5000),
            'harga_sewa' => fake()->randomElement([25000, 35000, 50000, 75000, 100000, 150000, 200000]),
            'status' => fake()->randomElement(['tersedia', 'tersedia', 'tersedia', 'disewa']),
            'deskripsi' => 'Properti ' . strtolower($type) . ' yang terletak di lokasi strategis di ' . $city . '. '
                . 'Fasilitas lengkap dengan akses jalan utama. '
                . 'Cocok untuk keperluan bisnis dan komersial. '
                . fake()->paragraph(2),
            'link_google_maps' => 'https://maps.google.com/?q=' . fake()->latitude(-7.5, -6.1) . ',' . fake()->longitude(106.5, 112.8),
            'thumbnail' => null,
        ];
    }

    /**
     * Indicate that the property is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tersedia',
        ]);
    }

    /**
     * Indicate that the property is rented.
     */
    public function rented(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disewa',
        ]);
    }
}
