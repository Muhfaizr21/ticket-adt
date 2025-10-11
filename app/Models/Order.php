<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_type_id',
        'quantity',
        'total_price',
        'barcode_code',
        'status',
        'refund_status',
        'refund_reason',
        'refunded_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi ke TicketType
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }
    public function payment()
    {
        return $this->hasOne(OrderPayment::class);
    }
}
