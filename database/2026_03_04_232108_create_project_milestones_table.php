<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->date('completed_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('status', ['en_attente', 'en_cours', 'termine', 'retarde'])->default('en_attente');
            $table->integer('order')->default(0);
            $table->json('deliverables')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['project_id', 'status']);
            $table->index('due_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_milestones');
    }
};