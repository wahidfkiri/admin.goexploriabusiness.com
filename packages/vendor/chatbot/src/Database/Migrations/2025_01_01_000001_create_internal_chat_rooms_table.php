<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();                         // null = direct 1:1
            $table->enum('type', ['direct', 'group'])->default('direct');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index('last_message_at');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_chat_rooms');
    }
};