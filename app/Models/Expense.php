<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'description',
        'category',
        'amount',
        'vendor',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }    protected static function booted()
    {
        static::creating(function ($expense) {
            $budget = Budget::find($expense->budget_id);
            if ($budget && ($budget->allocated + $expense->amount) > $budget->amount) {
                throw new \Exception('This expense would exceed the budget limit');
            }
        });

        static::created(function ($expense) {
            $budget = $expense->budget;
            if ($budget) {
                $budget->allocated += $expense->amount;
                $budget->save();
            }
        });

        static::deleted(function ($expense) {
            $budget = $expense->budget;
            if ($budget) {
                $budget->allocated -= $expense->amount;
                $budget->save();
            }
        });

        static::updated(function ($expense) {
            if ($expense->isDirty('amount') || $expense->isDirty('budget_id')) {
                $budget = Budget::find($expense->getOriginal('budget_id'));
                if ($budget) {
                    $budget->allocated -= $expense->getOriginal('amount');
                    $budget->save();
                }
                
                $newBudget = Budget::find($expense->budget_id);
                if ($newBudget) {
                    $newBudget->allocated += $expense->amount;
                    $newBudget->save();
                }
            }
        });
    }
} 