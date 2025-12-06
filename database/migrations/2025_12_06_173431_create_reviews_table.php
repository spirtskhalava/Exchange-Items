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
        Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The reviewer
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // The item being reviewed
        $table->integer('rating'); // 1 to 5
        $table->text('comment')->nullable();
        $table->timestamps();
        
        // Prevent a user from reviewing the same product twice
        $table->unique(['user_id', 'product_id']); 
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
