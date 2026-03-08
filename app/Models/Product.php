<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'etablissement_id',
        'product_category_id',
        'product_family_id',
        'main_type',
        'name',
        'slug',
        'reference',
        'barcode',
        'short_description',
        'long_description',
        'price_ht',
        'price_ttc',
        'tax_rate',
        'billing_unit',
        'purchase_price_ht',
        'cost_price_ht',
        'estimated_duration_minutes',
        'requires_appointment',
        'billing_period',
        'has_commitment',
        'commitment_months',
        'stock_management',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'stock_location',
        'main_image',
        'gallery_images',
        'documents',
        'is_public',
        'is_taxable',
        'is_available_for_sale',
        'availability_date',
        'end_sale_date',
        'commission_percentage',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'sales_count',
        'metadata'
    ];

    protected $casts = [
        'price_ht' => 'decimal:2',
        'price_ttc' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'purchase_price_ht' => 'decimal:2',
        'cost_price_ht' => 'decimal:2',
        'requires_appointment' => 'boolean',
        'has_commitment' => 'boolean',
        'is_public' => 'boolean',
        'is_taxable' => 'boolean',
        'is_available_for_sale' => 'boolean',
        'commission_percentage' => 'decimal:2',
        'gallery_images' => 'array',
        'documents' => 'array',
        'metadata' => 'array',
        'availability_date' => 'date',
        'end_sale_date' => 'date'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function family()
    {
        return $this->belongsTo(ProductFamily::class, 'product_family_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Product::class, 'package_components', 'component_id', 'package_id')
                    ->withPivot('quantity', 'discount_percentage', 'is_required', 'order')
                    ->withTimestamps();
    }

    public function components()
    {
        return $this->belongsToMany(Product::class, 'package_components', 'package_id', 'component_id')
                    ->withPivot('quantity', 'discount_percentage', 'is_required', 'order')
                    ->withTimestamps();
    }

    public function invoiceLines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function quoteLines()
    {
        return $this->hasMany(QuoteLine::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_rate', 'rate');
    }

    public function getCurrentPriceAttribute()
    {
        return $this->price_ttc;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price_ttc, 2) . ' €';
    }

    public function getInStockAttribute()
    {
        if ($this->stock_management === 'non') return true;
        if ($this->stock_management === 'sur_commande') return true;
        return $this->current_stock > 0;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available_for_sale', true)
                     ->where('is_public', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('main_type', $type);
    }
}