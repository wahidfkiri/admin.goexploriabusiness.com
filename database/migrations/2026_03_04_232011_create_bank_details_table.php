<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->string('bank_name')->nullable();
            $table->string('account_holder');
            $table->string('branch_code')->nullable();
            $table->string('transit_number')->nullable();
            $table->string('account_number');
            $table->string('iban')->nullable();
            $table->string('swift')->nullable();
            $table->string('rib_key')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('currency')->default('EUR');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'is_default']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_details');
    }
};