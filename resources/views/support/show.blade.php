@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('support.index') }}" class="text-xs font-bold text-muted-foreground hover:text-primary transition-colors flex items-center gap-1">
                    <span class="icon-[lucide--chevron-left] size-3"></span> Back to Tickets
                </a>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-[#1E0A3C]">{{ $ticket->subject }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-2">
                <span class="text-xs font-mono text-muted-foreground">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="badge badge-soft {{ $ticket->type === 'attendee_to_organizer' ? 'badge-info' : 'badge-warning' }} rounded-full px-3 text-[10px]">
                    {{ Str::title(str_replace('_', ' ', $ticket->type)) }}
                </span>
                <span class="badge {{ $ticket->status === 'open' ? 'badge-primary' : ($ticket->status === 'pending' ? 'badge-warning' : 'badge-success') }} rounded-full px-3 text-[10px]">
                    {{ ucfirst($ticket->status) }}
                </span>
                <span class="badge badge-outline rounded-full px-3 text-[10px] uppercase">
                    {{ $ticket->priority }} Priority
                </span>
            </div>
        </div>
        @if($ticket->status !== 'closed' && (Auth::user()->hasRole('admin') || Auth::id() === $ticket->recipient_id))
            <form action="#" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm rounded-full px-4">Close Ticket</button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 space-y-6">
            {{-- Message Thread --}}
            <div class="space-y-4">
                @foreach($ticket->messages as $message)
                    <div class="flex gap-4 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                        <div class="size-10 rounded-full bg-muted flex items-center justify-center shrink-0">
                            <span class="icon-[lucide--user] size-6 opacity-30"></span>
                        </div>
                        <div class="space-y-1 max-w-[80%] {{ $message->user_id === Auth::id() ? 'text-right' : '' }}">
                            <div class="flex items-center gap-2 {{ $message->user_id === Auth::id() ? 'justify-end' : '' }}">
                                <span class="font-bold text-sm text-[#1E0A3C]">{{ $message->user->name }}</span>
                                <span class="text-[10px] text-muted-foreground">{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="bg-card border rounded-2xl p-4 shadow-sm {{ $message->user_id === Auth::id() ? 'bg-primary/5 border-primary/20' : '' }}">
                                <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            @if($ticket->status !== 'closed')
                <div class="bg-card border rounded-2xl p-6 shadow-sm border-t-4 border-t-primary/30">
                    <h3 class="font-bold text-[#1E0A3C] mb-4">Post a Reply</h3>
                    <form action="{{ route('support.reply', $ticket) }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea name="message" class="textarea textarea-bordered rounded-xl w-full h-32 focus:border-primary" placeholder="Type your message here..." required></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary rounded-full px-8 shadow-lg shadow-primary/20">Send Reply</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-muted/30 border border-dashed rounded-2xl p-8 text-center">
                    <p class="text-muted-foreground">This ticket has been closed. No further replies can be posted.</p>
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            <div class="bg-card border rounded-xl p-5 shadow-sm space-y-4 text-sm">
                <h3 class="font-bold text-[#1E0A3C] border-b pb-2">Ticket Info</h3>
                <div>
                    <label class="text-[10px] font-bold uppercase text-muted-foreground block">Sender</label>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="size-6 rounded-full bg-muted flex items-center justify-center">
                             <span class="icon-[lucide--user] size-3"></span>
                        </div>
                        <span class="font-medium">{{ $ticket->sender->name }}</span>
                    </div>
                </div>
                @if($ticket->recipient)
                    <div>
                        <label class="text-[10px] font-bold uppercase text-muted-foreground block">Assigned To</label>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="size-6 rounded-full bg-muted flex items-center justify-center text-primary bg-primary/10">
                                 <span class="icon-[lucide--shield-check] size-3"></span>
                            </div>
                            <span class="font-medium">{{ $ticket->recipient->name }}</span>
                        </div>
                    </div>
                @endif
                @if($ticket->event)
                    <div>
                        <label class="text-[10px] font-bold uppercase text-muted-foreground block">Related Event</label>
                        <a href="{{ route('events.show', $ticket->event) }}" class="text-primary hover:underline font-medium block mt-1 line-clamp-1">
                            {{ $ticket->event->title }}
                        </a>
                    </div>
                @endif
                <div>
                    <label class="text-[10px] font-bold uppercase text-muted-foreground block">Initiated On</label>
                    <div class="font-medium mt-1">{{ $ticket->created_at->format('M d, Y • H:i') }}</div>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-muted-foreground block">Last Activity</label>
                    <div class="font-medium mt-1">{{ $ticket->updated_at->diffForHumans() }}</div>
                </div>
            </div>

            @if(Auth::user()->hasRole('admin'))
                <div class="bg-card border rounded-xl p-5 shadow-sm space-y-4 text-sm border-l-4 border-l-warning">
                    <h3 class="font-bold text-[#1E0A3C] border-b pb-2">Admin Tools</h3>
                    <div class="form-control">
                        <label class="label text-[10px] font-bold uppercase text-muted-foreground">Status</label>
                        <select class="select select-sm select-bordered rounded-lg">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <button class="btn btn-warning btn-sm btn-block rounded-full">Update Status</button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
