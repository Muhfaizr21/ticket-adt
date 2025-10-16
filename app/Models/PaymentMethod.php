<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'account_number',
        'account_name',
        'qr_code_image',
    ];

    /**
     * 🔥 Boot method untuk model PaymentMethod
     * Otomatis hapus QR code image saat record dihapus
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($paymentMethod) {
            if ($paymentMethod->qr_code_image && Storage::disk('public')->exists($paymentMethod->qr_code_image)) {
                Storage::disk('public')->delete($paymentMethod->qr_code_image);
            }
        });
    }
}
