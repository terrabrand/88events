<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Show the form for creating a new credit deposit.
     */
    public function create()
    {
        $users = User::role('organizer')->get(); // Only organizers really need credits
        return view('admin.credits.create', compact('users'));
    }

    /**
     * Store a newly created credit deposit in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Use the existing depositCredits method on the user model
        $user->depositCredits($request->amount, $request->description);

        return redirect()->route('admin.credits.create')
            ->with('success', "Successfully added {$request->amount} credits to {$user->name}'s account.");
    }
}
