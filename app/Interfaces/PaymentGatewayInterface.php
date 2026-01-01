<?php

namespace App\Interfaces;

interface PaymentGatewayInterface
{
    /**
     * Initialize the gateway with configuration.
     */
    public function initialize(array $config);

    /**
     * Create a payment intent or transaction.
     *
     * @param float $amount
     * @param string $currency
     * @param array $metadata
     * @return array
     */
    public function createPaymentIntent(float $amount, string $currency, array $metadata = []);

    /**
     * Verify a payment after it has been processed.
     *
     * @param string $paymentId
     * @return bool
     */
    public function verifyPayment(string $paymentId);
}
