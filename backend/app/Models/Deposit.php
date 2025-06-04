<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';

    protected $fillable = [
        'id_member',
        'jenis_simpanan',
        'total_simpanan',
        'created_at',
        'updated_at'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'id_deposit');
    }
}
