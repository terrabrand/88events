<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->creditTransactions()
            ->latest()
            ->paginate(10);
            
        return view('organizer.credits.index', compact('transactions'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway' => 'required|in:manual,stripe,paypal',
        ]);

        if ($request->gateway === 'manual') {
            // Redirect to support ticket creation with pre-filled details
            // Redirecting to the user-facing 'support.create' route
            return redirect()->route('support.create', [ 
                'subject' => "Payment Verification: \${$request->amount} Deposit",
                'message' => "I have made a payment of \${$request->amount} via Bank Transfer/Crypto. Please verify and add credits to my account." . PHP_EOL . PHP_EOL . "Transaction Details:"
            ]);
        }

        // Future integrations
        if (in_array($request->gateway, ['stripe', 'paypal'])) {
            return back()->with('error', 'Online payments are coming soon. Please use Manual / Bank Transfer for now.');
        }

        return back();
    }
}
