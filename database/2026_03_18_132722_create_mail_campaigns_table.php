<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mail_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('sujet');
            $table->longText('contenu');
            $table->json('options')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['status', 'scheduled_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_campaigns');
    }
};