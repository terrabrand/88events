<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;

class ReportController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        $event->reports()->create([
            'user_id' => $request->user()->id, // Assuming authenticated
            'reason' => $validated['reason'],
            'details' => key_exists('details', $validated) ? $validated['details'] : null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully. We will review it shortly.');
    }
}
