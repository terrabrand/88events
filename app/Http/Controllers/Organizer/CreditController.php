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
        ]);

        // Mock payment gateway logic
        $user = auth()->user();
        $user->depositCredits($request->amount, 'Manual Deposit');

        return back()->with('success', 'Credits added successfully!');
    }
}
