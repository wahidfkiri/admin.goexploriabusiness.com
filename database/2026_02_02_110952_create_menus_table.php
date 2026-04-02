<?php 

// database/migrations/xxxx_xx_xx_xxxxxx_create_menus_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['custom', 'category', 'activity'])->default('custom');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID de la catégorie ou activité');
            $table->integer('order')->default(0);
            $table->string('route')->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->index('parent_id');
            $table->index('type');
            $table->index('order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}