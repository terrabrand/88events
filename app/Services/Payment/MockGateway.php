<?php

namespace App\Services\Payment;

use Illuminate\Support\Str;

class MockGateway implements PaymentGatewayInterface
{
    public function charge(array $data): array
    {
        // Simulate API call delay
        // sleep(1);

        // Simulate random success/failure (90% success)
        $success = rand(1, 10) > 1;

        if ($success) {
            return [
                'success' => true,
                'transaction_ref' => $data['ref'],
                'message' => 'Payment initiated successfully (Mock)',
                'redirect_url' => route('payment.success', ['ref' => $data['ref']]), // For mock flow
            ];
        }

        return [
            'success' => false,
            'transaction_ref' => $data['ref'],
            'message' => 'Payment failed (Mock Error)',
        ];
    }

    public function handleCallback(array $payload): array
    {
        return [
            'transaction_ref' => $payload['ref'],
            'status' => 'completed',
            'external_ref' => 'MOCK-' . Str::random(10),
        ];
    }
}
