<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['event', 'ticketType'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('tickets.index', compact('tickets'));
    }

    public function purchase(Request $request, Event $event)
    {
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $ticketType = TicketType::findOrFail($request->ticket_type_id);

        if ($ticketType->event_id !== $event->id) {
            abort(403, 'Invalid ticket type for this event.');
        }

        if ($ticketType->available_quantity < $request->quantity) {
            return back()->with('error', 'Not enough tickets available.');
        }

        // DB Transaction would be better here
        for ($i = 0; $i < $request->quantity; $i++) {
            Ticket::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'ticket_code' => strtoupper(Str::random(10)), // Simple unique code
                'status' => 'valid',
            ]);
        }

        $ticketType->increment('quantity_sold', $request->quantity);

        return redirect()->route('tickets.index')->with('success', 'Tickets purchased successfully!');
    }

    public function download(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($ticket->ticket_code));

        $pdf = Pdf::loadView('tickets.pdf', compact('ticket', 'qrCode'));
        
        return $pdf->download("ticket-{$ticket->ticket_code}.pdf");
    }
}
