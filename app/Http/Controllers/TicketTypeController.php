<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketTypeController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sales_start_date' => 'nullable|date',
            'sales_end_date' => 'nullable|date|after_or_equal:sales_start_date',
        ]);

        $event->ticketTypes()->create($validated);

        return back()->with('success', 'Ticket Type added successfully.');
    }

    public function destroy(TicketType $ticketType)
    {
        $event = $ticketType->event;
        $this->authorize('update', $event);

        if ($ticketType->quantity_sold > 0) {
            return back()->with('error', 'Cannot delete ticket type with sales.');
        }

        $ticketType->delete();

        return back()->with('success', 'Ticket Type deleted.');
    }
}
