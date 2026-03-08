<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'categorie_type_id',
    ];

    public function websites()
    {
        return $this->hasMany(Website::class, 'categorie_id');
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'categorie_id');
    }

     
    public function activities()
    {
        // Spécifiez la clé étrangère si elle n'est pas 'category_id'
        return $this->hasMany(Activity::class, 'categorie_id'); // ou le nom correct de la colonne
    }

    /**
     * Get the type that owns the category.
     */
    public function type()
    {
        return $this->belongsTo(CategorieType::class, 'categorie_type_id');
    }
}
