<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'cms';

    public function up(): void
    {
        Schema::connection('cms')->create('cms_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etablissement_id')->index();
            $table->string('group')->default('general');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->json('options')->nullable();
            $table->timestamps();
            
            $table->unique(['etablissement_id', 'group', 'key']);
            $table->index(['etablissement_id', 'group']);
        });
    }

    public function down(): void
    {
        Schema::connection('cms')->dropIfExists('cms_settings');
    }
};