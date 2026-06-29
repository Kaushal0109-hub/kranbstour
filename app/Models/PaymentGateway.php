<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'is_test_mode',
        'credentials',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'credentials' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function meta(): array
    {
        return config("payment.gateways.{$this->slug}", []);
    }

    public function credential(string $key, ?string $default = null): ?string
    {
        return $this->credentials[$key] ?? $default;
    }

    public function isConfigured(): bool
    {
        $fields = $this->meta()['fields'] ?? [];

        foreach ($fields as $fieldKey => $field) {
            if (($field['required'] ?? false) && blank($this->credential($fieldKey))) {
                return false;
            }
        }

        return ! empty($fields);
    }

    public function isReady(): bool
    {
        return $this->is_active && $this->isConfigured();
    }

    public function modeLabel(): string
    {
        return $this->is_test_mode ? 'Test / Sandbox' : 'Live';
    }
}
