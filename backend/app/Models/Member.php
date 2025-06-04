<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'id_user',
        'nik',
        'address',
        'is_verified',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function collectors()
    {
        return $this->belongsToMany(Collector::class, 'member_collector', 'id_collector', 'id_member');

    }

}
