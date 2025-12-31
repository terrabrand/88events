@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-[#1E0A3C]">Support Tickets</h1>
            <p class="text-muted-foreground">Manage your communications and support requests.</p>
        </div>
        <div class="flex gap-3">
            @if(Auth::user()->hasRole('attendee'))
                <a href="{{ route('support.create', ['type' => 'attendee_to_organizer']) }}" class="btn btn-primary rounded-full px-6">Contact Organizer</a>
            @endif
            @if(Auth::user()->hasRole('organizer'))
                <a href="{{ route('support.create', ['type' => 'organizer_to_admin']) }}" class="btn btn-secondary rounded-full px-6">Contact Admin</a>
            @endif
        </div>
    </div>

    <div class="bg-card border rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-muted/50">
                        <th>Ticket</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="font-mono text-xs text-muted-foreground">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="badge badge-soft {{ $ticket->type === 'attendee_to_organizer' ? 'badge-info' : 'badge-warning' }} rounded-full px-3">
                                    {{ Str::title(str_replace('_', ' ', $ticket->type)) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-bold text-[#1E0A3C]">{{ $ticket->subject }}</div>
                                @if($ticket->event)
                                    <div class="text-xs text-muted-foreground">{{ $ticket->event->title }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $ticket->status === 'open' ? 'badge-primary' : ($ticket->status === 'pending' ? 'badge-warning' : 'badge-success') }} rounded-full px-3">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="text-sm text-muted-foreground">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('support.show', $ticket) }}" class="btn btn-ghost btn-sm rounded-full">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-20 text-muted-foreground">
                                <div class="mb-4">
                                    <span class="icon-[lucide--inbox] size-12 opacity-20"></span>
                                </div>
                                No support tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
            <div class="p-4 border-t border-border">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
