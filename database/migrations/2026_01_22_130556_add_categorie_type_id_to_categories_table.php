<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Add the foreign key column
            $table->foreignId('categorie_type_id')
                  ->nullable()
                  ->constrained('categorie_types')
                  ->onDelete('set null')
                  ->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['categorie_type_id']);
            $table->dropColumn('categorie_type_id');
        });
    }
};