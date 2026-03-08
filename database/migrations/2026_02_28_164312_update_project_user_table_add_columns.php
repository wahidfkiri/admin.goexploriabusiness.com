<?php
// database/migrations/[timestamp]_update_project_user_table_add_columns.php

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
        Schema::table('project_user', function (Blueprint $table) {
            // Ajouter assigned_at si elle n'existe pas
            if (!Schema::hasColumn('project_user', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('role');
            }
            
            // Ajouter d'autres colonnes potentielles qui pourraient être utiles
            if (!Schema::hasColumn('project_user', 'assigned_by')) {
                $table->foreignId('assigned_by')->nullable()->constrained('users')->after('assigned_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_user', function (Blueprint $table) {
            $table->dropColumn(['assigned_at', 'assigned_by']);
        });
    }
};