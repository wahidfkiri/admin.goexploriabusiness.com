<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mail_campaign_subscriber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('mail_campaigns')->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained('mail_subscribers')->onDelete('cascade');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'subscriber_id']);
            $table->index(['opened_at', 'clicked_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_campaign_subscriber');
    }
};