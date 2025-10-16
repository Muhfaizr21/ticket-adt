<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'position',
        'theme',
        'language',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is pengguna
     */
    public function isPengguna()
    {
        return $this->role === 'pengguna';
    }

    /**
     * Relasi ke tabel Order
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /**
     * 🔥 Boot method untuk model User
     * Otomatis hapus avatar saat user dihapus
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        });
    }
}
