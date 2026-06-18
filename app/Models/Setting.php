<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_perusahaan',
        'alamat_perusahaan',
        'no_whatsapp',
        'no_telepon',
        'email_perusahaan',
        'logo',
        'tentang',
    ];

    /**
     * Get the current settings (singleton pattern).
     */
    public static function current(): self
    {
        return self::first() ?? new self([
            'nama_perusahaan' => 'Telkom Property',
        ]);
    }

    /**
     * Generate WhatsApp link.
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        if ($this->no_whatsapp) {
            $number = preg_replace('/[^0-9]/', '', $this->no_whatsapp);
            // Convert leading 0 to 62 (Indonesia)
            if (str_starts_with($number, '0')) {
                $number = '62' . substr($number, 1);
            }
            return "https://wa.me/{$number}";
        }

        return null;
    }
}
