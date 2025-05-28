<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'coordinates',
        'title',
        'description',
        'household_id',
        'color',
        'project_id'
    ];

    protected $casts = [
        'coordinates' => 'array'
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class);
    }
} 