<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function payment()
    {
        return view('admin.settings.payment');
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'paypal_client_id' => 'nullable|string',
            'paypal_mode' => 'nullable|in:sandbox,live',
            'paystack_public_key' => 'nullable|string',
            'paystack_merchant_email' => 'nullable|email',
            'bank_account_details' => 'nullable|string',
        ]);

        $keys = [
            'stripe_enabled', 'stripe_public_key', 'stripe_secret_key',
            'paypal_enabled', 'paypal_client_id', 'paypal_secret', 'paypal_mode',
            'paystack_enabled', 'paystack_public_key', 'paystack_secret_key', 'paystack_merchant_email',
            'razorpay_enabled', 'razorpay_key_id', 'razorpay_key_secret',
            'bank_transfer_enabled', 'bank_account_details',
            'cash_on_hand_enabled', 'cash_instructions'
        ];

        foreach ($keys as $key) {
            if (strpos($key, '_enabled') !== false) {
                \App\Models\Setting::set($key, $request->has($key) ? '1' : '0');
            } else {
                \App\Models\Setting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'Global payment configurations and gateway availability have been updated.');
    }
}
