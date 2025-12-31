<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['sender', 'recipient', 'event'])
            ->latest()
            ->paginate(15);

        return view('admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['messages.user', 'sender', 'recipient', 'event']);
        return view('admin.support.show', compact('ticket'));
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,pending,closed',
        ]);

        $ticket->update(['status' => $validated['status']]);

        return back()->with('success', 'Ticket status updated successfully.');
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $ticket->update(['status' => 'pending']);

        return back()->with('success', 'Reply sent successfully.');
    }
}
