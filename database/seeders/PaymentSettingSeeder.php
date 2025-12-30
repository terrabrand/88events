<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Bank Transfer
            'bank_transfer_enabled' => '0',
            'bank_account_details' => '',

            // Cash on Hand
            'cash_on_hand_enabled' => '0',
            'cash_instructions' => 'Pay cash at the event entrance.',

            // Paypal
            'paypal_enabled' => '0',
            'paypal_client_id' => '',
            'paypal_secret' => '',
            'paypal_mode' => 'sandbox',

            // Paystack
            'paystack_enabled' => '0',
            'paystack_public_key' => '',
            'paystack_secret_key' => '',
            'paystack_merchant_email' => '',

            // Razorpay
            'razorpay_enabled' => '0',
            'razorpay_key_id' => '',
            'razorpay_key_secret' => '',

            // Stripe
            'stripe_enabled' => '0',
            'stripe_public_key' => '',
            'stripe_secret_key' => '',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
