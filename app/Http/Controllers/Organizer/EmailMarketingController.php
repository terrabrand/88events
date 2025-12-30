<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Services\MailchimpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailMarketingController extends Controller
{
    protected $mailchimp;

    public function __construct(MailchimpService $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }

    public function index()
    {
        $organizer = Auth::user();
        $events = Event::where('organizer_id', $organizer->id)->withCount('tickets')->get();
        $subscription = $organizer->activeSubscription();

        return view('organizer.email.index', compact('events', 'subscription'));
    }

    public function create(Event $event)
    {
        $this->authorize('update', $event);
        $attendeeCount = $event->tickets()->distinct('user_id')->count();
        $organizer = Auth::user();
        $subscription = $organizer->activeSubscription();

        return view('organizer.email.create', compact('event', 'attendeeCount', 'subscription'));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $organizer = Auth::user();
        $subscription = $organizer->activeSubscription();

        if (!$subscription) {
            return back()->with('error', 'You need an active subscription to send marketing emails.');
        }

        $attendeeCount = $event->tickets()->distinct('user_id')->count();

        if ($subscription->email_used + $attendeeCount > $subscription->package->email_limit) {
            return back()->with('error', 'You do not have enough Email credits in your package.');
        }

        // In a real scenario, we would sync attendees to a Mailchimp segments/tags first.
        // For this implementation, we trigger a campaign send.
        $success = $this->mailchimp->sendCampaign(
            $request->subject,
            $request->content,
            $organizer->email,
            $organizer->name
        );

        if ($success) {
            $subscription->increment('email_used', $attendeeCount);
            return redirect()->route('organizer.email.index')->with('success', "Successfully queued email campaign for $attendeeCount attendees.");
        }

        return back()->with('error', 'Failed to send email campaign via Mailchimp. Check credentials.');
    }
}
