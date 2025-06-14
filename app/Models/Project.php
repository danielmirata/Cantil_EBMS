<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'description',
        'start_date',
        'end_date',
        'budget',
        'status',
        'location',
        'priority',
        'funding_source',
        'notes',
        'documents',
        'progress'
    ];

    protected $casts = [
        'documents' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2'
    ];
}