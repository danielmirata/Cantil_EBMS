<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $table = 'residents_information';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'residency_status',
        'voters',
        'pwd',
        'pwd_type',
        'single_parent',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'civil_status',
        'nationality',
        'religion',
        'email',
        'contact_number',
        'house_number',
        'street',
        'barangay',
        'municipality',
        'zip',
        'household_id'
    ];

    public function mapLocations()
    {
        return $this->hasMany(MapLocation::class);
    }

    public function getFullNameAttribute()
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix
        ])));
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }
} 