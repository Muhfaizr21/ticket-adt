<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'author',
        'published_at',
    ];

    // 🕒 Agar published_at otomatis jadi Carbon (bisa ->format)
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
