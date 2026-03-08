<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageComponent extends Model
{
    use HasFactory;

    protected $table = 'package_components';

    protected $fillable = [
        'package_id',
        'component_id',
        'quantity',
        'discount_percentage',
        'is_required',
        'order',
        'metadata'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'discount_percentage' => 'decimal:2',
        'is_required' => 'boolean',
        'order' => 'integer',
        'metadata' => 'array'
    ];

    public function package()
    {
        return $this->belongsTo(Product::class, 'package_id');
    }

    public function component()
    {
        return $this->belongsTo(Product::class, 'component_id');
    }

    public function getTotalPriceAttribute()
    {
        $price = $this->component->price_ttc * $this->quantity;
        if ($this->discount_percentage > 0) {
            $price = $price * (1 - $this->discount_percentage / 100);
        }
        return $price;
    }
}