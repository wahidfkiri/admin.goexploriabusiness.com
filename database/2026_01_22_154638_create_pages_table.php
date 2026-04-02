<?php
// database/migrations/xxxx_xx_xx_create_pages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('html_content')->nullable();
            $table->text('css_content')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Clés polymorphiques
            $table->unsignedBigInteger('pageable_id');
            $table->string('pageable_type');
            
            $table->timestamps();
            
            // Index
            $table->index(['pageable_id', 'pageable_type']);
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
}