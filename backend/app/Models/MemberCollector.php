<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCollector extends Model
{
    use HasFactory;

    protected $table = 'member_collector';

    protected $fillable = [
        'id_collector',
        'id_member',
        'tgl_penugasan',
    ];

    protected $casts = [
        'tgl_penugasan' => 'date',
    ];

    // Relasi ke Collector
    public function collector()
    {
        return $this->belongsTo(Collector::class, 'id_collector');
    }

    // Relasi ke Member
    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }
}
