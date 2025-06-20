<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;
    protected $table = 'installments';

    protected $fillable = [
        'id_loan',
        'tgl_pembayaran',
        'cicilan_ke',
        'besar_ciclan',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
    'tgl_pembayaran' => 'datetime',
    ];

    // relasi ke loan
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'id_loan');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id_installment');
    }
}
