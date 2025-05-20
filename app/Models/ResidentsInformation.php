<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidentsInformation extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'residents_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the full name of the resident.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim("$this->first_name $this->middle_name $this->last_name $this->suffix");
    }

    /**
     * Get the age of the resident.
     *
     * @return int
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }
}
