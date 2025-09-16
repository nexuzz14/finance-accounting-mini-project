<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'customer',
        'amount',
        'tax_amount',
        'status'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    protected $appends = ['total_amount'];

    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->tax_amount;
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount_paid');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount + $this->tax_amount - $this->total_paid;
    }

    public function updateStatus()
    {
        $totalPaid = $this->total_paid;
        $totalAmount = $this->amount + $this->tax_amount;

        if ($totalPaid == 0) {
            $this->status = 'open';
        } elseif ($totalPaid >= $totalAmount) {
            $this->status = 'paid';
        } else {
            $this->status = 'partial';
        }

        $this->save();
    }
}
