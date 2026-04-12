<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')
                  ->constrained('internal_chat_rooms')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->text('body')->nullable();
            $table->enum('type', ['text', 'file', 'event'])->default('text');
            $table->json('metadata')->nullable();                             // ex: nom du fichier, taille, mime
            $table->timestamp('deleted_at')->nullable();                      // soft delete message
            $table->timestamps();

            // Index critique pour long-polling (WHERE room_id = X AND id > Y)
            $table->index(['room_id', 'id']);
            $table->index(['room_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_chat_messages');
    }
};