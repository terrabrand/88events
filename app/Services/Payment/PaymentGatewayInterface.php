<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    /**
     * Initiate a payment request.
     *
     * @param array $data ['amount', 'currency', 'phone', 'email', 'ref']
     * @return array ['success' => bool, 'transaction_ref' => string, 'message' => string]
     */
    public function charge(array $data): array;

    /**
     * Handle webhook/callback from gateway.
     * 
     * @param array $payload
     * @return array ['transaction_ref' => string, 'status' => string, 'external_ref' => string]
     */
    public function handleCallback(array $payload): array;
}
