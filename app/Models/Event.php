<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venue;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'date',
        'location',
        'total_tickets',
        'available_tickets',
        'poster',
        'venue_id',
        'vip_tickets',
        'vip_price',
        'reguler_tickets',
        'reguler_price'
    ];

    // Relasi ke Venue
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
