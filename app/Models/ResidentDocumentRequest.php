<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentDocumentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'address',
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
