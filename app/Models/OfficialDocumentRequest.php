<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficialDocumentRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_id',
        'user_id',
        'document_type',
        'purpose',
        'additional_info',
        'date_needed',
        'status',
        'remarks'
    ];

    protected $dates = [
        'deleted_at',
        'date_needed'
    ];

    protected $casts = [
        'date_needed' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($documentRequest) {
            $documentRequest->request_id = 'BR' . str_pad(static::count() + 1, 5, '0', STR_PAD_LEFT);
        });
    }
} 