<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_type_id',
        'event_id',
        'code',
        'name',
        'persen_diskon',
        'value',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
