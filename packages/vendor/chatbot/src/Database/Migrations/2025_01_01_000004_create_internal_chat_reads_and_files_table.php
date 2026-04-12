<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Suivi de lecture par room/user — un seul enregistrement mis à jour
        Schema::create('internal_chat_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')
                  ->constrained('internal_chat_rooms')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->unsignedBigInteger('last_read_message_id')->default(0);
            $table->timestamp('read_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['room_id', 'user_id']);
            $table->index('user_id');
        });

        // Fichiers attachés aux messages internes
        Schema::create('internal_chat_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')
                  ->constrained('internal_chat_messages')
                  ->cascadeOnDelete();
            $table->foreignId('room_id')
                  ->constrained('internal_chat_rooms')
                  ->cascadeOnDelete();
            $table->foreignId('uploaded_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('mime_type', 100);
            $table->string('extension', 10);
            $table->unsignedInteger('size');                                  // octets
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->boolean('is_image')->default(false);
            $table->timestamps();

            $table->index('room_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_chat_files');
        Schema::dropIfExists('internal_chat_reads');
    }
};