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
    'total_tickets',
    'available_tickets',
    'poster',
];
}
