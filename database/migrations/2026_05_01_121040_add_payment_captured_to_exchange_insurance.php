<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_insurance', function (Blueprint $table) {
            // True only after PayPal capture is confirmed for each side
            $table->boolean('req_payment_captured')->default(false)->after('req_paypal_order_id');
            $table->boolean('resp_payment_captured')->default(false)->after('resp_paypal_order_id');
        });
    }

    public function down()
    {
        Schema::table('exchange_insurance', function (Blueprint $table) {
            $table->dropColumn(['req_payment_captured', 'resp_payment_captured']);
        });
    }
};
