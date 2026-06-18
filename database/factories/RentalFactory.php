<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    protected $model = Rental::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-12 months', 'now');
        $endDate = fake()->dateTimeBetween($startDate, '+24 months');

        return [
            'nama_penyewa' => fake()->company() ?: fake()->name(),
            'no_telepon' => '08' . fake()->numerify('##########'),
            'email_penyewa' => fake()->companyEmail(),
            'property_id' => Property::factory(),
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'status_sewa' => 'aktif',
            'catatan' => fake()->optional(0.5)->sentence(),
        ];
    }

    /**
     * Indicate that the rental is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_sewa' => 'selesai',
            'tanggal_mulai' => fake()->dateTimeBetween('-24 months', '-6 months'),
            'tanggal_selesai' => fake()->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }
}
