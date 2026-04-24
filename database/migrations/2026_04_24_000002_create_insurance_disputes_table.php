<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceDisputesTable extends Migration
{
    public function up()
    {
        Schema::create('insurance_disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_id')->constrained()->onDelete('cascade');
            $table->foreignId('filed_by')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->json('evidence_paths')->nullable();
            $table->enum('status', ['pending', 'resolved_filer', 'resolved_other', 'dismissed'])
                  ->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('insurance_disputes');
    }
}
