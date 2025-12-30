<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Check if user has already reviewed
        if ($event->reviews()->where('user_id', $request->user()->id)->exists()) {
            return back()->with('error', 'You have already reviewed this event.');
        }

        // Optional: Check if user has attended the event (requires more complex logic checking tickets/attendance)
        
        $event->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto-approve for now
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}
