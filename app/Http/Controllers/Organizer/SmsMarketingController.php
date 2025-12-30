<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsMarketingController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function index()
    {
        $organizer = Auth::user();
        $events = Event::where('organizer_id', $organizer->id)->withCount('tickets')->get();
        $subscription = $organizer->activeSubscription();

        return view('organizer.sms.index', compact('events', 'subscription'));
    }

    public function create(Event $event)
    {
        $this->authorize('update', $event);
        $attendeeCount = $event->tickets()->distinct('user_id')->count();
        $organizer = Auth::user();
        $subscription = $organizer->activeSubscription();

        return view('organizer.sms.create', compact('event', 'attendeeCount', 'subscription'));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'message' => 'required|string|max:160',
        ]);

        $organizer = Auth::user();
        $subscription = $organizer->activeSubscription();

        if (!$subscription) {
            return back()->with('error', 'You need an active subscription to send SMS.');
        }

        $attendees = User::whereIn('id', $event->tickets()->pluck('user_id')->toArray())
            ->whereNotNull('phone')
            ->get();

        $count = $attendees->count();

        if ($subscription->sms_used + $count > $subscription->package->sms_limit) {
            return back()->with('error', 'You do not have enough SMS credits in your package.');
        }

        $successCount = 0;
        foreach ($attendees as $attendee) {
            if ($this->twilio->sendSms($attendee->phone, $request->message)) {
                $successCount++;
            }
        }

        $subscription->increment('sms_used', $successCount);

        return redirect()->route('organizer.sms.index')->with('success', "Successfully sent SMS to $successCount attendees.");
    }
}
