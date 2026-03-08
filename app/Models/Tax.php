<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'rate',
        'type',
        'is_default',
        'is_active',
        'description',
        'metadata'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'tax_id');
    }

    public function invoiceLines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function quoteLines()
    {
        return $this->hasMany(QuoteLine::class);
    }
}