<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'house_number',
        'street',
        'barangay',
        'municipality',
        'zip_code',
        'latitude',
        'longitude'
    ];

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}
