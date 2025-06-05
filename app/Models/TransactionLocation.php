<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLocation extends Model
{
    protected $table = 'transaction_location';

    use HasFactory;

    protected $fillable = [
        'id_transaction',
        'latitude',
        'longitude',
        'location_name',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
