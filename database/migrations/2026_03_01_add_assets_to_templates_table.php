<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('url')->nullable()->after('name');
            $table->text('js_content')->nullable()->after('css_content');
            $table->string('assets_folder')->nullable()->after('js_content');
            $table->json('assets_data')->nullable()->after('assets_folder');
        });
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn(['url', 'js_content', 'assets_folder', 'assets_data']);
        });
    }
};