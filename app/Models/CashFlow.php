<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;

    protected $table = 'cash_flow';

    protected $fillable = [
        'transaction_date',
        'nominal',
        'is_cash_in',
        'type',
        'description',
        'created_by',
        'related_transaction_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'is_cash_in' => 'boolean',
        'amount' => 'decimal:2',
    ];

    // Relasi ke User (Admin)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi opsional ke Transactions
    public function relatedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'related_transaction_id');
    }

    // Scope untuk kas masuk
    public function scopeCashIn($query)
    {
        return $query->where('is_cash_in', true);
    }

    // Scope untuk kas keluar
    public function scopeCashOut($query)
    {
        return $query->where('is_cash_in', false);
    }
}
