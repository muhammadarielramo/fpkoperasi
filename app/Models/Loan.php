<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $table = 'loans';

    protected $fillable = [
        'id_member',
        'tgl_pengajuan',
        'nama_pinjaman',
        'jumlah_pinjaman',
        'tenor',
        'status',
        'tgl_persetujuan',
        'created_at',
        'updated_at'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function installments()  {
        return $this->hasMany(Installment::class, 'id_loan');
    }
}
