<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'normal_balance',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function journalLines()
    {
        return $this->hasMany(JournalLine::class, 'account_id');
    }
}
