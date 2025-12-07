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
    Schema::create('user_reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->integer('rating'); // 1 to 5
        $table->text('comment')->nullable();
        $table->timestamps();

        // Optional: Prevent a user from reviewing the same person multiple times
        // $table->unique(['reviewer_id', 'user_id']);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_reviews');
    }
};
