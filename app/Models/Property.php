<?php

namespace App\Models;

use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    /** @use HasFactory<PropertyFactory> */
    use HasFactory;
    /**
     * The table associated with the model.
     */
    protected $table = 'properties';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_properti',
        'alamat',
        'luas',
        'harga_sewa',
        'status',
        'deskripsi',
        'link_google_maps',
        'thumbnail',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'luas' => 'decimal:2',
            'harga_sewa' => 'decimal:2',
        ];
    }

    /**
     * Get the gallery images for the property.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'property_id')->orderBy('sort_order');
    }

    /**
     * Get the rentals for the property.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'property_id');
    }

    /**
     * Get the active rental for the property.
     */
    public function activeRental()
    {
        return $this->rentals()->where('status_sewa', 'aktif')->latest()->first();
    }

    /**
     * Check if the property is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'tersedia';
    }

    /**
     * Calculate the total monthly rent based on area.
     * Harga sewa = Rp/meter/bulan × luas
     */
    public function getTotalSewaPerBulanAttribute(): float
    {
        return $this->harga_sewa * $this->luas;
    }

    /**
     * Calculate remaining area (sisa luas).
     */
    public function getSisaLuasAttribute(): float
    {
        $activeRented = $this->rentals()->where('status_sewa', 'aktif')->sum('luas_sewa');
        return max(0, $this->luas - $activeRented);
    }

    /**
     * Sync property status based on remaining area.
     */
    public function syncStatus()
    {
        if ($this->sisa_luas <= 0) {
            $this->update(['status' => 'disewa']);
        } else {
            $this->update(['status' => 'tersedia']);
        }
    }

    /**
     * Calculate total rent per day (assuming 30 days/month).
     */
    public function getTotalSewaPerHariAttribute(): float
    {
        return $this->total_sewa_per_bulan / 30;
    }

    /**
     * Calculate total rent per year (12 months).
     */
    public function getTotalSewaPerTahunAttribute(): float
    {
        return $this->total_sewa_per_bulan * 12;
    }
}
