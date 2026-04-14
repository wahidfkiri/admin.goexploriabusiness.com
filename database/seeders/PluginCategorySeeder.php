<?php
// database/seeders/PluginCategorySeeder.php

namespace Database\Seeders;

use App\Models\PluginCategory;
use Illuminate\Database\Seeder;

class PluginCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Analytics', 'slug' => 'analytics', 'icon' => 'fas fa-chart-bar', 'order' => 1],
            ['name' => 'Marketing', 'slug' => 'marketing', 'icon' => 'fas fa-bullhorn', 'order' => 2],
            ['name' => 'Sécurité', 'slug' => 'securite', 'icon' => 'fas fa-shield-alt', 'order' => 3],
            ['name' => 'E-commerce', 'slug' => 'e-commerce', 'icon' => 'fas fa-shopping-cart', 'order' => 4],
            ['name' => 'Productivité', 'slug' => 'productivite', 'icon' => 'fas fa-cog', 'order' => 5],
            ['name' => 'Intégrations', 'slug' => 'integrations', 'icon' => 'fas fa-plug', 'order' => 6],
            ['name' => 'Communication', 'slug' => 'communication', 'icon' => 'fas fa-comments', 'order' => 7],
            ['name' => 'Réseaux sociaux', 'slug' => 'reseaux-sociaux', 'icon' => 'fas fa-users', 'order' => 8],
            ['name' => 'Automatisation', 'slug' => 'automatisation', 'icon' => 'fas fa-robot', 'order' => 9],
            ['name' => 'Divers', 'slug' => 'divers', 'icon' => 'fas fa-ellipsis-h', 'order' => 10],
        ];

        foreach ($categories as $category) {
            PluginCategory::create($category);
        }
    }
}