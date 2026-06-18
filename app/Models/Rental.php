<?php

namespace App\Models;

use Database\Factories\RentalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rental extends Model
{
    /** @use HasFactory<RentalFactory> */
    use HasFactory;
    /**
     * The table associated with the model.
     */
    protected $table = 'rentals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_penyewa',
        'no_telepon',
        'email_penyewa',
        'property_id',
        'luas_sewa',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_sewa',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'luas_sewa' => 'decimal:2',
        ];
    }

    /**
     * Get the property associated with this rental.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Get the payment associated with this rental.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Check if the rental is currently active.
     */
    public function isActive(): bool
    {
        return $this->status_sewa === 'aktif';
    }

    /**
     * Calculate duration of the rental in months.
     */
    public function getDurasiSewaAttribute(): int
    {
        return max(1, $this->tanggal_mulai->diffInMonths($this->tanggal_selesai)); // at least 1 month
    }

    /**
     * Calculate the total rental revenue.
     * Total = harga_sewa × luas_sewa × durasi (bulan)
     */
    public function getTotalPendapatanAttribute(): float
    {
        if ($this->property) {
            $luas = $this->luas_sewa > 0 ? $this->luas_sewa : $this->property->luas;
            return $this->property->harga_sewa * $luas * $this->durasi_sewa;
        }

        return 0;
    }

    /**
     * Calculate the tax amount (11% of total revenue).
     */
    public function getPajakAttribute(): float
    {
        return round($this->total_pendapatan * 0.11);
    }

    /**
     * Calculate the total billing amount (revenue + tax).
     */
    public function getTotalTagihanAttribute(): float
    {
        return $this->total_pendapatan + $this->pajak;
    }
}
