<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_chat_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')
                  ->constrained('internal_chat_rooms')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->enum('role', ['member', 'admin'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('muted_until')->nullable();                     // silence notifications
            $table->timestamps();

            $table->unique(['room_id', 'user_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_chat_participants');
    }
};