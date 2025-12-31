@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-[#1E0A3C]">System Support Overview</h1>
        <p class="text-muted-foreground">Manage all support tickets from organizers and attendees.</p>
    </div>

    <div class="bg-card border rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-muted/50">
                        <th>Ticket</th>
                        <th>Type</th>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="font-mono text-xs text-muted-foreground">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="badge badge-soft {{ $ticket->type === 'attendee_to_organizer' ? 'badge-info' : 'badge-warning' }} rounded-full px-3 text-[10px]">
                                    {{ Str::title(str_replace('_', ' ', $ticket->type)) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-medium text-sm">{{ $ticket->sender->name }}</div>
                                <div class="text-[10px] text-muted-foreground">{{ ucfirst($ticket->sender->roles->first()?->name ?? 'User') }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-[#1E0A3C]">{{ $ticket->subject }}</div>
                                @if($ticket->event)
                                    <div class="text-xs text-muted-foreground">Event: {{ $ticket->event->title }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="text-xs font-bold uppercase {{ $ticket->priority === 'high' ? 'text-error' : ($ticket->priority === 'medium' ? 'text-warning' : 'text-info') }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $ticket->status === 'open' ? 'badge-primary' : ($ticket->status === 'pending' ? 'badge-warning' : 'badge-success') }} rounded-full px-3 text-[10px]">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.support.show', $ticket) }}" class="btn btn-ghost btn-sm rounded-full">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-20 text-muted-foreground">
                                No support tickets to display.
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
