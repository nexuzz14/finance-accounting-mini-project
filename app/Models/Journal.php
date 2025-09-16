<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no',
        'posting_date',
        'memo',
        'status',
        'created_by'
    ];

    protected $casts = [
        'posting_date' => 'date',
    ];

    public function journalLines()
    {
        return $this->hasMany(JournalLine::class);
    }

    public function getTotalDebitAttribute()
    {
        return $this->journalLines()->sum('debit');
    }

    public function getTotalCreditAttribute()
    {
        return $this->journalLines()->sum('credit');
    }
}
