<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','event_id','quantity','total_price','status'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
