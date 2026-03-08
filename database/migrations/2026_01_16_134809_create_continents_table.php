<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Dans la migration create_continents_table
public function up()
{
    Schema::create('continents', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code', 2)->unique();
        $table->string('image')->nullable();
        $table->text('description')->nullable();
        $table->bigInteger('population')->nullable(); // Changé de integer à bigInteger
        $table->decimal('area', 15, 2)->nullable();
        $table->integer('countries_count')->nullable();
        $table->json('languages')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    public function down()
    {
        Schema::dropIfExists('continents');
    }
};