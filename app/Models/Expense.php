<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'description',
        'category',
        'amount',
        'vendor',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
} 