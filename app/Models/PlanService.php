<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanService extends Model
{
    use HasFactory;

    protected $table = 'plan_services';

    protected $fillable = [
        'plan_id',
        'title',
        'slug',
        'description',
        'content',
        'service_type',
        'price',
        'currency',
        'is_active',
        'sort_order',
        'main_media_type',
        'main_image_path',
        'main_video_path',
        'main_video_url',
        'gallery',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'gallery' => 'array',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

