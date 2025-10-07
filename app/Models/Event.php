<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'price',
        'status',
        'total_tickets',
        'available_tickets',
        'venue_id',
        'poster',
        'vip_tickets',
        'vip_price',
        'reguler_tickets',
        'reguler_price',
    ];

    // Relasi ke Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relasi ke Venue (opsional)
    public function venue()
{
    return $this->belongsTo(Venue::class);
}
public function events()
{
    return $this->hasMany(Event::class);
}


}
