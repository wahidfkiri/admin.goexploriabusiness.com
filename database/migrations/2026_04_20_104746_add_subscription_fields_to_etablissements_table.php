<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('etablissements', function (Blueprint $table) {
            $table->foreignId('current_abonnement_id')->nullable()->constrained('abonnements')->nullOnDelete()->after('email_contact');
            $table->date('subscription_expires_at')->nullable()->after('email_contact');
            $table->enum('subscription_status', ['active', 'expired', 'none'])->default('none')->after('email_contact');
            $table->string('stripe_customer_id')->nullable()->after('email_contact');
            $table->string('stripe_subscription_id')->nullable()->after('email_contact');
        });
    }

    public function down()
    {
        Schema::table('etablissements', function (Blueprint $table) {
            $table->dropForeign(['current_abonnement_id']);
            $table->dropColumn(['current_abonnement_id', 'subscription_expires_at', 'subscription_status', 'stripe_customer_id', 'stripe_subscription_id']);
        });
    }
};