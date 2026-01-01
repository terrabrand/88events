<?php

namespace App\Services\Payment;

use App\Interfaces\PaymentGatewayInterface;

class ManualGateway implements PaymentGatewayInterface
{
    public function initialize(array $config)
    {
        // No init needed for manual
    }

    public function createPaymentIntent(float $amount, string $currency, array $metadata = [])
    {
        // For manual payments, we just return instructions or a placeholder
        return [
            'id' => 'manual_' . uniqid(),
            'status' => 'pending',
            'redirect_url' => route('support.create', ['subject' => 'Payment Verification: Manual Transfer']), // Direct user to verify via support
        ];
    }

    public function verifyPayment(string $paymentId)
    {
        // Manual payments are verified manually by admin
        return false;
    }
}
