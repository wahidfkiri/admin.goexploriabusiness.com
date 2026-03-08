<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Dans la migration create_countries_table
public function up()
{
    Schema::create('countries', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code', 3)->unique();
        $table->string('iso2', 2)->unique()->nullable();
        $table->string('phone_code')->nullable();
        $table->string('capital')->nullable();
        $table->string('currency')->nullable();
        $table->string('currency_symbol')->nullable();
        $table->string('flag')->nullable();
        $table->string('latitude')->nullable();
        $table->string('longitude')->nullable();
        $table->text('description')->nullable();
        $table->bigInteger('population')->nullable(); // Changé ici aussi
        $table->decimal('area', 15, 2)->nullable();
        $table->string('official_language')->nullable();
        $table->json('timezones')->nullable();
        $table->string('region')->nullable();
        $table->foreignId('continent_id')->constrained('continents')->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });
}

    public function down()
    {
        Schema::dropIfExists('countries');
    }
};