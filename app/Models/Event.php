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
    'total_tickets',
    'available_tickets',
    'poster',
    'venue_id'
];

public function venue()
{
    return $this->belongsTo(Venue::class);
}
}
