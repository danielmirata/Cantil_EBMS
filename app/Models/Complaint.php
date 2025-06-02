<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'contact_number',
        'email',
        'purok',
        'complainee_name',
        'complainee_address',
        'complaint_type',
        'incident_date',
        'incident_time',
        'incident_location',
        'complaint_description',
        'evidence_photo',
        'declaration',
        'status',
        'remarks'
    ];

    protected $casts = [
        'incident_date' => 'date',
        'incident_time' => 'datetime',
        'declaration' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blotter()
    {
        return $this->hasOne(Blotter::class);
    }
} 