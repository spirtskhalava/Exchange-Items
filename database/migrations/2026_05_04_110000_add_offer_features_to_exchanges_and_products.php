<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Products: preferred category for incoming offers
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'preferred_offer_category')) {
                $table->string('preferred_offer_category')->nullable()->after('looking_for');
            }
        });

        // Exchanges: cancellation request flow
        Schema::table('exchanges', function (Blueprint $table) {
            if (!Schema::hasColumn('exchanges', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('exchanges', 'cancel_requested_at')) {
                $table->timestamp('cancel_requested_at')->nullable()->after('cancel_reason');
            }
            if (!Schema::hasColumn('exchanges', 'cancel_approved')) {
                // null = pending, true = approved, false = rejected
                $table->boolean('cancel_approved')->nullable()->after('cancel_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('preferred_offer_category');
        });
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'cancel_requested_at', 'cancel_approved']);
        });
    }
};
