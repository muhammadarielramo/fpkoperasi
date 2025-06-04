<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'id_collector',
        'id_anggota',
        'id_loan',
        'id_installment',
        'id_deposit',
        'tipe_transaksi',
        'tgl_transaksi',
        'jumlah',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tgl_setoran' => 'date',
        'jml_setoran' => 'decimal:2',
    ];

    // Relasi ke Collector
    public function collector()
    {
        return $this->belongsTo(Collector::class, 'id_collector');
    }

    // Relasi ke Member
    public function member()
    {
        return $this->belongsTo(Member::class, 'id_anggota');
    }

    // Relasi ke Loan
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'id_loan');
    }

    // Relasi ke Deposit
    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'id_deposit');
    }
}
