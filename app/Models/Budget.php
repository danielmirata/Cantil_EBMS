<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'period',
        'description',
        'remaining_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function getUsedAmountAttribute()
    {
        return $this->amount - $this->remaining_amount;
    }

    public function getUsagePercentageAttribute()
    {
        if ($this->amount == 0) return 0;
        return ($this->used_amount / $this->amount) * 100;
    }
} 