<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class OrganizerProfileController extends Controller
{
    public function show(User $organizer)
    {
        // Ensure the user has the organizer role? 
        // Or just allow any user to be viewed if they have events? 
        // For now, let's assume any user can be viewed, but typically we link to organizers.
        
        $organizer->loadCount('followers');
        $organizer->loadCount('following');
        
        $events = Event::where('organizer_id', $organizer->id)
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->with(['venue', 'category'])
            ->paginate(12);

        return view('organizer.profile.show', compact('organizer', 'events'));
    }
}
