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
        Schema::table('internal_chat_messages', function (Blueprint $table) {
            $table->index(['room_id', 'id'], 'idx_room_id_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_chat_messages', function (Blueprint $table) {
            $table->dropIndex('idx_room_id_id');
        });
    }
};