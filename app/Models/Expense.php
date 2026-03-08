<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expense_number',
        'etablissement_id',
        'expense_category_id',
        'project_id',
        'user_id',
        'title',
        'description',
        'expense_date',
        'amount_ht',
        'tax_amount',
        'amount_ttc',
        'tax_rate',
        'supplier',
        'invoice_number',
        'payment_method',
        'status',
        'is_recurring',
        'recurring_frequency',
        'next_recurring_date',
        'attachments',
        'metadata',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'next_recurring_date' => 'date',
        'approved_at' => 'datetime',
        'amount_ht' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'amount_ttc' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_recurring' => 'boolean',
        'attachments' => 'array',
        'metadata' => 'array'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approve($userId)
    {
        $this->status = 'approuve';
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->save();
    }

    public function reject($reason = null)
    {
        $this->status = 'rejete';
        $this->description = ($this->description ? $this->description . "\n" : '') . "Rejeté: " . $reason;
        $this->save();
    }
}