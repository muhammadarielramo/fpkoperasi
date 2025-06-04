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

    public function collectors()
    {
        return $this->belongsToMany(Collector::class, 'member_collector', 'id_member', 'id_collector');

    }

    public function deposit() {
        return $this->hasMany(Deposit::class, 'id_member');
    }

    public function loan() {
        return $this->hasMany(Loan::class, 'id_member');
    }

    public function transaction() {
        return $this->hasMany(Transaction::class, 'id_anggota');
    }
}
