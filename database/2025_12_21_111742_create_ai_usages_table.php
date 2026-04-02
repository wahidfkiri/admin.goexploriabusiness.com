<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('model');
            $table->integer('prompt_tokens');
            $table->integer('completion_tokens');
            $table->integer('total_tokens');
            $table->decimal('estimated_cost', 10, 4);
            $table->string('endpoint')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usages');
    }
};