<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'etablissement_id',
        'bank_name',
        'account_holder',
        'branch_code',
        'transit_number',
        'account_number',
        'iban',
        'swift',
        'rib_key',
        'is_default',
        'currency',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'metadata' => 'array'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
}