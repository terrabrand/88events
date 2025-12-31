<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $location = $request->input('l');

        $eventsQuery = Event::where('status', 'published')->with(['venue', 'ticketTypes']);

        if ($query) {
            $eventsQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($location) {
            $eventsQuery->where(function ($q) use ($location) {
                $q->where('venue_address', 'like', "%{$location}%")
                  ->orWhereHas('venue', function ($v) use ($location) {
                      $v->where('name', 'like', "%{$location}%")
                        ->orWhere('city', 'like', "%{$location}%")
                        ->orWhere('state', 'like', "%{$location}%")
                        ->orWhere('country', 'like', "%{$location}%");
                  });
            });
        }

        $events = $eventsQuery->latest()->paginate(12)->withQueryString();

        return view('pages.search', compact('events', 'query', 'location'));
    }
}
