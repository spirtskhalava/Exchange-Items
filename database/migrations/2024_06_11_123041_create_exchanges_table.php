<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangesTable extends Migration
{
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('responder_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('requested_product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('offered_product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->decimal('money_offer', 10, 2)->nullable(); // Add money_offer column with decimal type
            $table->enum('status', ['pending', 'accepted', 'declined', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exchanges');
    }
}