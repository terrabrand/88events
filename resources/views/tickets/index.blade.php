@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <h2 class="text-3xl font-bold tracking-tight text-foreground">My Tickets</h2>

        @if(session('success'))
             <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
                <span class="icon-[tabler--check] size-5"></span>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tickets as $ticket)
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm transition-shadow hover:shadow-md">
                    <figure class="h-40 overflow-hidden relative rounded-t-xl">
                         @if($ticket->event->cover_image_path)
                            <img src="{{ asset('storage/' . $ticket->event->cover_image_path) }}" alt="{{ $ticket->event->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="bg-muted text-muted-foreground h-full w-full flex items-center justify-center">
                                <span class="font-bold">EVENT</span>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                {{ $ticket->status === 'valid' ? 'border-transparent bg-green-500/80 text-white' : 'border-transparent bg-gray-500/80 text-white' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </figure>
                    <div class="p-6">
                        <div class="text-sm text-muted-foreground mb-1">{{ $ticket->event->start_date->format('M d, Y h:i A') }}</div>
                        <h3 class="font-semibold leading-none tracking-tight text-lg mb-4">
                            <a href="{{ route('events.show', $ticket->event) }}" class="hover:underline">
                                {{ $ticket->event->title }}
                            </a>
                        </h3>
                        <div class="flex items-center justify-between mb-6">
                            <span class="inline-flex items-center rounded-full border border-border px-2.5 py-0.5 text-xs font-semibold text-foreground">
                                {{ $ticket->ticketType->name }}
                            </span>
                            @if($ticket->seat_number)
                                <span class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-2.5 py-0.5 text-xs font-bold text-primary">
                                    Seat: {{ $ticket->seat_number }}
                                </span>
                            @endif
                            <span class="font-mono text-xs text-muted-foreground bg-muted px-2 py-1 rounded">{{ $ticket->ticket_code }}</span>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="{{ route('tickets.download', $ticket) }}" class="btn btn-primary btn-sm">
                                <span class="icon-[tabler--download] size-4"></span>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 opacity-60 text-muted-foreground">
                    <p>You haven't purchased any tickets yet.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </div>
@endsection

