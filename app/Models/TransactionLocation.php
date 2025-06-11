<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLocation extends Model
{
    protected $table = 'location_transaction';

    use HasFactory;

    protected $fillable = [
        'id_transaction',
        'latitude',
        'longitude',
        'location_name',
        'collector_id'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function collector()
    {
        return $this->belongsTo(Collector::class);
    }
}
