<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'available_tickets',
        'poster',
        'venue_id',
    ];

    /**
     * Cast otomatis
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Relasi ke Venue
     * Setiap event dimiliki oleh satu venue (opsional)
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Relasi ke TicketType
     * Satu event bisa memiliki banyak tipe tiket
     */
    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    /**
     * Relasi ke Order
     * Satu event bisa memiliki banyak order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    // app/Models/Event.php
    public function getAvailableTicketsAttribute()
    {
        // Menjumlahkan semua 'available_tickets' dari setiap tipe tiket milik event ini
        return $this->ticketTypes()->sum('available_tickets');
    }
}
