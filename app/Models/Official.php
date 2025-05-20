<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Official extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
        'position_id',
        'term_start',
        'term_end',
        'status',
        'profile_picture',
        'archived_at'
    ];

    protected $dates = [
        'deleted_at',
        'archived_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'term_start' => 'date',
        'term_end' => 'date',
        'archived_at' => 'datetime'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }
} 