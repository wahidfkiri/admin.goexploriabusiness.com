<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crawled_pages', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->longText('html');
            $table->longText('css')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawled_pages');
    }
};
