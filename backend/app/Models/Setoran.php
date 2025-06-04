<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory;

    protected $table = 'setoran';

    protected $fillable = [
        'id_collector',
        'tgl_setoran',
        'jml_setoran',
        'bukti_setoran',
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
}
