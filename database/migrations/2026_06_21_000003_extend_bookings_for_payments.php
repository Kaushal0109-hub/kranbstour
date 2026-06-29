<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE bookings MODIFY user_id BIGINT UNSIGNED NULL');
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_ref', 32)->nullable()->unique()->after('id');
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone', 30)->nullable()->after('customer_email');
            $table->string('payment_option', 20)->default('arrival')->after('notes');
            $table->string('payment_status', 20)->default('pending')->after('payment_option');
            $table->decimal('unit_price', 10, 2)->default(0)->after('payment_status');
            $table->decimal('total_amount', 10, 2)->default(0)->after('unit_price');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('total_amount');
            $table->decimal('amount_due', 10, 2)->default(0)->after('amount_paid');
            $table->string('currency', 3)->default('USD')->after('amount_due');
            $table->string('paypal_order_id')->nullable()->after('currency');
            $table->string('paypal_capture_id')->nullable()->after('paypal_order_id');
            $table->string('pickup_location')->nullable()->after('paypal_capture_id');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'booking_ref',
                'customer_name',
                'customer_email',
                'customer_phone',
                'payment_option',
                'payment_status',
                'unit_price',
                'total_amount',
                'amount_paid',
                'amount_due',
                'currency',
                'paypal_order_id',
                'paypal_capture_id',
                'pickup_location',
            ]);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE bookings MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
