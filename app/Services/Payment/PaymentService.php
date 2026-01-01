<?php

namespace App\Services\Payment;

use App\Interfaces\PaymentGatewayInterface;
use InvalidArgumentException;

class PaymentService
{
    protected $gateway;

    public function __construct(string $gatewayName)
    {
        $this->gateway = $this->resolveGateway($gatewayName);
    }

    protected function resolveGateway(string $name): PaymentGatewayInterface
    {
        // For now, we'll return a stub or specific implementations as we build them
        // This switch will expand as we implement Stripe, PayPal, etc.
        switch ($name) {
            case 'manual':
                return new ManualGateway();
            // case 'stripe':
            //     return new StripeGateway();
            default:
                throw new InvalidArgumentException("Payment gateway [{$name}] is not supported.");
        }
    }

    public function getGateway(): PaymentGatewayInterface
    {
        return $this->gateway;
    }
}
