<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetEmail extends Model
{
    protected $table = 'reset_email';

    protected $fillable = [
        'id_user',
        'token',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
