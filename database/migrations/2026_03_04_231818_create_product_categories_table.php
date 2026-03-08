<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->onDelete('cascade');
            $table->foreignId('product_family_id')->nullable()->constrained('product_families')->onDelete('set null');
            $table->foreignId('categorie_type_id')->nullable()->constrained('categorie_types')->onDelete('set null');
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['product_family_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
};