<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'email_verified_at',
        'kode_otp',
        'password',
        'username',
        'name',
        'phone_number',
        'id_role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    // Relasi ke Member
    public function member()
    {
        return $this->hasOne(Member::class, 'id_user');
    }

    // Relasi ke Collector
    public function collector()
    {
        return $this->hasOne(Collector::class, 'id_user');
    }
}
