<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 30)->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->json('credentials')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_gateway', 30)->nullable()->after('payment_status');
            $table->string('gateway_order_id')->nullable()->after('payment_gateway');
            $table->string('gateway_transaction_id')->nullable()->after('gateway_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_gateway', 'gateway_order_id', 'gateway_transaction_id']);
        });

        Schema::dropIfExists('payment_gateways');
    }
};
