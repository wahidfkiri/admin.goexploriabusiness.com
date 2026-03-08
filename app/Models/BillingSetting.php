<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'etablissement_id',
        'hide_invoice_button',
        'last_invoice_number',
        'invoice_prefix',
        'invoice_number_length',
        'tax_number_tps',
        'tax_number_tvq',
        'neq',
        'default_shipping_fees',
        'default_administration_fees',
        'default_discount_percentage',
        'cheque_order',
        'bank_details',
        'payment_button_code',
        'procedure',
        'instructions',
        'default_note',
        'payment_deadline_days',
        'quote_validity_days',
        'legal_mentions',
        'rcs_number',
        'siret',
        'metadata'
    ];

    protected $casts = [
        'hide_invoice_button' => 'boolean',
        'default_shipping_fees' => 'decimal:2',
        'default_administration_fees' => 'decimal:2',
        'default_discount_percentage' => 'decimal:2',
        'bank_details' => 'array',
        'metadata' => 'array',
        'payment_deadline_days' => 'integer',
        'quote_validity_days' => 'integer'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function getNextInvoiceNumber()
    {
        $last = $this->last_invoice_number;
        $prefix = $this->invoice_prefix;
        $number = intval(str_replace($prefix, '', $last));
        $next = $number + 1;
        
        return $prefix . str_pad($next, $this->invoice_number_length, '0', STR_PAD_LEFT);
    }
}