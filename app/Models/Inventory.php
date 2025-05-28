<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'quantity',
        'unit',
        'cost',
        'supplier'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cost' => 'decimal:2'
    ];
} 