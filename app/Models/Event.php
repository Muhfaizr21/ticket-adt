<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'poster',
        'venue_id',
    ];

    protected $casts = [
        'date'       => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    /**
     * 🔗 Relasi ke Venue
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * 🔗 Relasi ke TicketType
     */
    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    /**
     * 🔗 Relasi ke Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 🧮 Hitung total tiket tersedia dari semua tipe tiket
     */
    public function getAvailableTicketsAttribute()
    {
        return $this->ticketTypes()->sum('available_tickets');
    }

    /**
     * 🕒 Hitung durasi event dalam jam & menit
     */
    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::parse($this->start_time);
            $end   = Carbon::parse($this->end_time);
            return $start->diff($end)->format('%h jam %i menit');
        }
        return null;
    }

    /**
     * 🔥 Boot method untuk event model
     * Otomatis hapus poster & tiket terkait saat event dihapus
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($event) {
            // Hapus poster jika ada
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }

            // Hapus semua tiket terkait
            $event->ticketTypes()->delete();
        });
    }
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'event_id');
    }
}
