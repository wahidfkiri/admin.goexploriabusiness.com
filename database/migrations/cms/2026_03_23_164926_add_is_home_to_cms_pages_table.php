<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'cms';

    public function up(): void
    {
        Schema::connection('cms')->table('cms_pages', function (Blueprint $table) {
            if (!Schema::connection('cms')->hasColumn('cms_pages', 'is_home')) {
                $table->boolean('is_home')->default(false)->after('slug');
                $table->index(['etablissement_id', 'is_home']);
            }
        });
    }

    public function down(): void
    {
        Schema::connection('cms')->table('cms_pages', function (Blueprint $table) {
            if (Schema::connection('cms')->hasColumn('cms_pages', 'is_home')) {
                $table->dropColumn('is_home');
            }
        });
    }
};