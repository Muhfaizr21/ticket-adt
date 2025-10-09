<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'total_tickets',
        'available_tickets',
        'discount_type',
        'discount_value',
        'discount_start',
        'discount_end',
    ];

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Hitung harga setelah diskon
    public function getDiscountedPriceAttribute()
    {
        if (!$this->discount_type || !$this->discount_value) {
            return $this->price;
        }

        $today = now();

        if ($this->discount_start && $this->discount_end) {
            if ($today->lt($this->discount_start) || $today->gt($this->discount_end)) {
                return $this->price; // diskon tidak berlaku
            }
        }

        if ($this->discount_type === 'percent') {
            return round($this->price * (1 - $this->discount_value / 100), 2);
        } elseif ($this->discount_type === 'nominal') {
            return max(0, $this->price - $this->discount_value);
        }

        return $this->price;
    }

    // Status diskon aktif
    public function getDiscountStatusAttribute()
    {
        $today = now();
        if (
            $this->discount_type && $this->discount_value &&
            (!$this->discount_start || !$this->discount_end ||
                ($today->between($this->discount_start, $this->discount_end)))
        ) {
            return true;
        }
        return false;
    }
    /**
     * Relasi ke Promotions
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'ticket_type_id');
    }
}
