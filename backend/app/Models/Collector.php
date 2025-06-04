<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collector extends Model
{
    use HasFactory;

    protected $table = 'collectors';

    protected $fillable = [
        'id_user',
        'status',
        'created_at',
        'updated_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Setoran
    public function setorans()
    {
        return $this->hasMany(Setoran::class, 'id_collector');
    }

    // Relasi ke MemberCollector
    public function memberCollectors()
    {
        return $this->hasMany(MemberCollector::class, 'id_collector');
    }

    // Collector.php
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_collector', 'id_collector', 'id_member');
    }

    public function transaction(){
        return $this->hasMany(Transaction::class, 'id_collector');
    }
}
