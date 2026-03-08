<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'etablissement_id',
        'client_id',
        'project_id',
        'quote_id',
        'invoice_date',
        'due_date',
        'payment_date',
        'subtotal',
        'shipping_fees',
        'administration_fees',
        'discount_percentage',
        'discount_amount',
        'tax_total',
        'total',
        'paid_amount',
        'remaining_amount',
        'taxes_breakdown',
        'status',
        'installments_count',
        'payment_method',
        'payment_details',
        'client_name',
        'client_address',
        'client_zipcode',
        'client_city',
        'client_country',
        'client_vat_number',
        'notes',
        'internal_notes',
        'footer',
        'metadata'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'subtotal' => 'decimal:2',
        'shipping_fees' => 'decimal:2',
        'administration_fees' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'taxes_breakdown' => 'array',
        'metadata' => 'array'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'invoice_tax')->withPivot('amount', 'rate');
    }

    public function getIsPaidAttribute()
    {
        return $this->remaining_amount <= 0;
    }

    public function getIsOverdueAttribute()
    {
        return !$this->is_paid && $this->due_date < now();
    }

    public function updatePaidAmount()
    {
        $this->paid_amount = $this->payments()->where('status', 'complete')->sum('amount');
        $this->remaining_amount = $this->total - $this->paid_amount;
        
        if ($this->remaining_amount <= 0) {
            $this->status = 'payee';
            $this->payment_date = now();
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partiellement_payee';
        }
        
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $billingSettings = BillingSetting::where('etablissement_id', $invoice->etablissement_id)->first();
                if ($billingSettings) {
                    $invoice->invoice_number = $billingSettings->getNextInvoiceNumber();
                }
            }
        });
    }
}