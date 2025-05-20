<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'purpose',
        'status',
        'remarks',
        'document_file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 