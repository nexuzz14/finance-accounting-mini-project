<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_ref',
        'paid_at',
        'amount_paid',
        'method'
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->invoice->updateStatus();
        });

        static::deleted(function ($payment) {
            $payment->invoice->updateStatus();
        });
    }
}
