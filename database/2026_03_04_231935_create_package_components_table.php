<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['package_id', 'component_id'], 'package_component_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_components');
    }
};