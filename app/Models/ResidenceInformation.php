<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidenceInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'residents_information';
    
    protected $dates = [
        'deleted_at',
        'archived_at'
    ];

    protected $fillable = [
        'profile_picture',
        'residency_status',
        'voters',
        'pwd',
        'pwd_type',
        'single_parent',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
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
        'father_name',
        'mother_name',
        'guardian_name',
        'guardian_contact',
        'guardian_relation',
        'household_id'
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }
}