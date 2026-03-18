<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mail_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('mail_campaigns')->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained('mail_subscribers')->onDelete('cascade');
            $table->enum('event_type', ['open', 'click', 'bounce', 'complaint']);
            $table->json('payload')->nullable();
            $table->timestamps();
            
            $table->index(['campaign_id', 'event_type']);
            $table->index(['subscriber_id', 'event_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mail_tracking_events');
    }
};