<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrawledPage extends Model
{
    protected $fillable = [
        'url',
        'html',
        'css',
    ];
}
