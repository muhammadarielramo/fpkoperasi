<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;
    protected $table = 'members';

    protected $fillable = [
        'id_user',
        'nik',
        'address',
        'is_verified',
        'bod',
        'foto_ktp',
        'gender',
        'created_at',
        'updated_at',
    ];


    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function collector()
    {
        return $this->hasOne(Collector::class);
    }


    public function deposit() {
        return $this->hasMany(Deposit::class, 'id_member');
    }

    public function loan() {
        return $this->hasMany(Loan::class, 'id_member');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'id_anggota');
    }
}
