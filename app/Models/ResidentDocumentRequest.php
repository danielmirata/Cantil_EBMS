<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentDocumentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'fullname',
        'contact_number',
        'email',
        'purok',
        'date_needed',
        'purpose',
        'notes',
        'id_type',
        'id_photo',
        'declaration',
        'status',
        'remarks'
    ];

    protected $casts = [
        'date_needed' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
