<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'date',
        'description',
        'category',
        'amount',
        'status',
        'location',
        'beneficiary',
        'receipt_number',
        'receipt_file_path',
        'budget_allocation',
        'budget_remaining'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'budget_remaining' => 'decimal:2'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByBudgetAllocation($query, $allocation)
    {
        return $query->where('budget_allocation', $allocation);
    }
} 