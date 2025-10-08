<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_type_id',
        'event_id',
        'code',
        'name',
        'type',
        'value',
        'start_date',
        'end_date',
        'is_active',
    ];

    // Relasi ke TicketType
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    // Relasi ke Event langsung (optional, tapi buat safety)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Cek promo aktif
    public function isCurrentlyActive(): bool
    {
        $today = Carbon::today();
        return $this->is_active &&
            $today->between(Carbon::parse($this->start_date), Carbon::parse($this->end_date));
    }

    // Hitung harga setelah diskon
    public function getDiscountedPrice(float $originalPrice): float
    {
        if (! $this->isCurrentlyActive()) return $originalPrice;

        return $this->type === 'percentage'
            ? max(0, $originalPrice - ($originalPrice * $this->value / 100))
            : max(0, $originalPrice - $this->value);
    }
}
