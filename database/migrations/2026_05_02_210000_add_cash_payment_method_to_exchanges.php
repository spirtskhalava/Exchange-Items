<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('exchanges', function (Blueprint $table) {
            // 'paypal' or 'cash' — set when requester chooses how to pay
            $table->string('cash_payment_method')->nullable()->after('cash_payment_captured');
        });
    }
    public function down(): void {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dropColumn('cash_payment_method');
        });
    }
};
