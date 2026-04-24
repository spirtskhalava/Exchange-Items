<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeInsuranceTable extends Migration
{
    public function up()
    {
        Schema::create('exchange_insurance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_id')->unique()->constrained()->onDelete('cascade');

            $table->boolean('req_opted')->default(false);
            $table->boolean('resp_opted')->default(false);

            // PayPal order IDs for escrow tracking
            $table->string('req_paypal_order_id')->nullable();
            $table->string('resp_paypal_order_id')->nullable();

            // Requester's item valuation (their offered_product)
            $table->decimal('req_item_value', 10, 2)->nullable();
            $table->string('req_item_proposed_by')->nullable(); // 'requester' or 'responder'
            $table->boolean('req_item_agreed')->default(false);

            // Responder's item valuation (their requested_product)
            $table->decimal('resp_item_value', 10, 2)->nullable();
            $table->string('resp_item_proposed_by')->nullable();
            $table->boolean('resp_item_agreed')->default(false);

            $table->enum('escrow_status', ['none', 'negotiating', 'pending_payment', 'locked', 'released', 'disputed'])
                  ->default('none');

            $table->boolean('req_received')->default(false);
            $table->boolean('resp_received')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exchange_insurance');
    }
}
