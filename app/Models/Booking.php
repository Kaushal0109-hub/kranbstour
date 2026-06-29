<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'booking_ref',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'package_id',
        'category_slug',
        'package_slug',
        'package_title',
        'city',
        'price',
        'travel_date',
        'travelers',
        'status',
        'notes',
        'pickup_location',
        'pickup_latitude',
        'pickup_longitude',
        'payment_option',
        'payment_status',
        'unit_price',
        'total_amount',
        'amount_paid',
        'amount_due',
        'currency',
        'payment_gateway',
        'gateway_order_id',
        'gateway_transaction_id',
        'paypal_order_id',
        'paypal_capture_id',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_due' => 'decimal:2',
        'pickup_latitude' => 'decimal:7',
        'pickup_longitude' => 'decimal:7',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tourPackage(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    public function customerDisplayName(): string
    {
        return $this->user?->name ?? $this->customer_name ?? 'Guest';
    }

    public function customerDisplayEmail(): ?string
    {
        return $this->user?->email ?? $this->customer_email;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            default => 'Pending',
        };
    }

    public function paymentOptionLabel(): string
    {
        return match ($this->payment_option) {
            'deposit' => '30% deposit & reserve',
            'full' => '100% paid online',
            default => 'Pay on arrival',
        };
    }

    public function paymentStatusLabel(): string
    {
        return match ($this->payment_status) {
            'partial' => '30% paid',
            'paid' => 'Fully paid',
            'pay_on_arrival' => 'Pay on arrival',
            default => 'Payment pending',
        };
    }
}
