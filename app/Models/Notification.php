<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    use HasFactory;

    protected $fillable = [
        'id_user',
        'type',
        'title',
        'message',
        'read_at',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime', // Mengubah ke objek Carbon datetime
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Sesuaikan dengan model User Anda
    }
}
