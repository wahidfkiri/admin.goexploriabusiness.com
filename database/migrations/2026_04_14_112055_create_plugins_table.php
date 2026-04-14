<?php
// database/migrations/2024_01_01_000000_create_plugins_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('version');
            $table->string('author');
            $table->string('author_website')->nullable();
            $table->string('icon')->nullable(); // SVG content or class
            $table->enum('price_type', ['free', 'paid'])->default('free');
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('type', ['core', 'official', 'third-party', 'custom'])->default('third-party');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->integer('category_id')->nullable();
            $table->float('rating')->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('downloads')->default(0);
            $table->json('settings')->nullable();
            $table->json('compatibility')->nullable(); // PHP versions, Laravel versions etc.
            $table->text('changelog')->nullable();
            $table->string('documentation_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->boolean('is_core')->default(false);
            $table->boolean('can_be_disabled')->default(true);
            $table->boolean('can_be_uninstalled')->default(true);
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};