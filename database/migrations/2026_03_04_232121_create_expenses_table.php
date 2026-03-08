<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique();
            $table->foreignId('etablissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('expense_category_id')->constrained('expense_categories')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->decimal('amount_ht', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('amount_ttc', 10, 2);
            $table->decimal('tax_rate', 5, 2)->nullable();
            
            $table->string('supplier')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('payment_method')->nullable();
            
            $table->enum('status', ['en_attente', 'approuve', 'rejete', 'rembourse'])->default('en_attente');
            
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['mensuel', 'trimestriel', 'annuel'])->nullable();
            $table->date('next_recurring_date')->nullable();
            
            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['etablissement_id', 'expense_date']);
            $table->index(['project_id', 'expense_category_id']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};