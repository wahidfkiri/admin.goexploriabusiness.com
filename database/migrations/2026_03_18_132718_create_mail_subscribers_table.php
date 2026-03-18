<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mail_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->unique();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->boolean('is_subscribed')->default(true);
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_subscribed', 'etablissement_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_subscribers');
    }
};