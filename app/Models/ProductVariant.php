<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'barcode',
        'attributes',
        'price_adjustment',
        'sale_price',
        'stock',
        'image',
        'is_default',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'attributes' => 'array',
        'price_adjustment' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    protected $attributes = [
        'attributes' => '{}', // Valeur par défaut JSON vide
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute()
    {
        $basePrice = $this->product->price_ttc;
        return $basePrice + $this->price_adjustment;
    }

    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    // Boot method pour définir des valeurs par défaut à la création
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (empty($variant->attributes)) {
                $variant->attributes = ['default' => true];
            }
            
            if (empty($variant->is_active)) {
                $variant->is_active = true;
            }
        });
    }
}