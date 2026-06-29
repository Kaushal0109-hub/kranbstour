<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Services\Payment\PaymentGatewayManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentGatewayController extends Controller
{
    public function __construct(private PaymentGatewayManager $gateways) {}

    public function index(): View
    {
        return view('admin.master.payment-gateways.index', [
            'gateways' => $this->gateways->all(),
        ]);
    }

    public function edit(PaymentGateway $paymentGateway): View
    {
        return view('admin.master.payment-gateways.edit', [
            'gateway' => $paymentGateway,
            'meta' => $paymentGateway->meta(),
        ]);
    }

    public function update(Request $request, PaymentGateway $paymentGateway): RedirectResponse
    {
        $meta = $paymentGateway->meta();
        $fields = $meta['fields'] ?? [];

        $rules = [
            'is_active' => ['nullable', 'boolean'],
            'is_test_mode' => ['nullable', 'boolean'],
        ];

        foreach ($fields as $key => $field) {
            $rules["credentials.{$key}"] = [($field['required'] ?? false) ? 'nullable' : 'nullable', 'string', 'max:500'];
        }

        $validated = $request->validate($rules);

        $credentials = $paymentGateway->credentials ?? [];
        foreach (array_keys($fields) as $key) {
            $value = $request->input("credentials.{$key}");
            if ($value !== null && $value !== '') {
                $credentials[$key] = $value;
            }
        }

        $paymentGateway->update([
            'is_active' => $request->boolean('is_active'),
            'is_test_mode' => $request->boolean('is_test_mode'),
            'credentials' => $credentials ?: null,
        ]);

        return redirect()
            ->route('admin.master.payment-gateways.index')
            ->with('success', $paymentGateway->name.' settings saved.');
    }
}
