<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venue;

class Event extends Model
{
    use HasFactory;

protected $fillable = [
    'name', 'description', 'date', 'location', 'price',
    'total_tickets', 'available_tickets', 'venue_id', 'poster'
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
