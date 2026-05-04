<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->string('cash_paypal_order_id')->nullable()->after('money_offer');
            $table->boolean('cash_payment_captured')->default(false)->after('cash_paypal_order_id');
        });
    }
    public function down(): void {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dropColumn(['cash_paypal_order_id', 'cash_payment_captured']);
        });
    }
};
