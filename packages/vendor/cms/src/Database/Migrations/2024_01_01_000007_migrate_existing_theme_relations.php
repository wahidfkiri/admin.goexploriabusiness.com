<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'cms';

    public function up(): void
    {
        // Migrer les relations existantes
        $themes = DB::connection('cms')->table('cms_themes')->get();
        
        foreach ($themes as $theme) {
            if ($theme->etablissement_id) {
                DB::connection('cms')->table('cms_etablissement_theme')->insert([
                    'etablissement_id' => $theme->etablissement_id,
                    'theme_id' => $theme->id,
                    'is_active' => $theme->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::connection('cms')->table('cms_etablissement_theme')->truncate();
    }
};