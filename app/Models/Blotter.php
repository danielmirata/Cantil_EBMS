<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    use HasFactory;

    protected $fillable = [
        // Complainant Details
        'blotters_id',
        'first_name',
        'last_name',
        'age',
        'sex',
        'civil_status',
        'complete_address',
        'contact_number',
        'occupation',
        'relationship_to_respondent',
        'email',

        // Respondent Details
        'respondent_first_name',
        'respondent_last_name',
        'respondent_age',
        'respondent_sex',
        'respondent_civil_status',
        'respondent_address',
        'respondent_contact_number',
        'respondent_occupation',

        // Incident Details
        'complaint_type',
        'incident_date',
        'incident_time',
        'incident_location',
        'complaint_description',
        'what_happened',
        'who_was_involved',
        'how_it_happened',

        // Witness Details
        'witness_name',
        'witness_address',
        'witness_contact',

        // Action Taken by Barangay
        'initial_action_taken',
        'handling_officer_name',
        'handling_officer_position',
        'mediation_date',
        'action_result',
        'remarks',

        // Additional Fields
        'evidence_photo',
        'declaration',
        'status'
    ];

    protected $casts = [
        'incident_date' => 'date',
        'incident_time' => 'datetime',
        'mediation_date' => 'date',
        'declaration' => 'boolean',
        'age' => 'integer',
        'respondent_age' => 'integer'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
} 