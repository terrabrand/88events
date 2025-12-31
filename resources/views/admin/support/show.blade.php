@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.support.index') }}" class="text-xs font-bold text-muted-foreground hover:text-primary transition-colors flex items-center gap-1">
                    <span class="icon-[lucide--chevron-left] size-3"></span> Back to All Tickets
                </a>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-[#1E0A3C]">{{ $ticket->subject }} (Admin View)</h1>
            <div class="flex flex-wrap items-center gap-3 mt-2">
                <span class="text-xs font-mono text-muted-foreground">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="badge badge-soft {{ $ticket->type === 'attendee_to_organizer' ? 'badge-info' : 'badge-warning' }} rounded-full px-3 text-xs">
                    {{ Str::title(str_replace('_', ' ', $ticket->type)) }}
                </span>
                <span class="badge {{ $ticket->status === 'open' ? 'badge-primary' : ($ticket->status === 'pending' ? 'badge-warning' : 'badge-success') }} rounded-full px-3 text-xs">
                    {{ ucfirst($ticket->status) }}
                </span>
                <span class="badge badge-outline rounded-full px-3 text-xs uppercase">
                    {{ $ticket->priority }} Priority
                </span>
            </div>
        </div>
        
        <div class="flex gap-2">
            <form action="{{ route('admin.support.status', $ticket) }}" method="POST" id="status-form-{{ $ticket->id }}">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="select select-sm select-bordered rounded-lg bg-card">
                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </form>
        </div>
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
                                @if($message->user->hasRole('admin'))
                                    <span class="badge badge-error text-[8px] px-1 rounded">ADMIN</span>
                                @endif
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
                <div class="bg-card border rounded-2xl p-6 shadow-sm border-t-4 border-t-error/30">
                    <h3 class="font-bold text-[#1E0A3C] mb-4">Official Admin Response</h3>
                    <form action="{{ route('admin.support.reply', $ticket) }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea name="message" class="textarea textarea-bordered rounded-xl w-full h-32 focus:border-error" placeholder="Type your official response here..." required></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-error rounded-full px-8 shadow-lg shadow-error/20">Send Response</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6 text-sm">
            <div class="bg-card border rounded-xl p-5 shadow-sm space-y-4">
                <h3 class="font-bold text-[#1E0A3C] border-b pb-2">User Details</h3>
                <div>
                    <label class="text-[10px] font-bold uppercase text-muted-foreground block">Sender</label>
                    <div class="font-medium mt-1">{{ $ticket->sender->name }}</div>
                    <div class="text-xs text-muted-foreground">{{ $ticket->sender->email }}</div>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-muted-foreground block">Role</label>
                    <span class="badge badge-soft rounded-full px-2 text-[10px] mt-1">
                        {{ ucfirst($ticket->sender->roles->first()?->name ?? 'User') }}
                    </span>
                </div>
                @if($ticket->recipient)
                    <div>
                        <label class="text-[10px] font-bold uppercase text-muted-foreground block">Recipient</label>
                        <div class="font-medium mt-1">{{ $ticket->recipient->name }}</div>
                    </div>
                @endif
                @if($ticket->event)
                    <div>
                        <label class="text-[10px] font-bold uppercase text-muted-foreground block">Related Event</label>
                        <div class="font-medium mt-1">{{ $ticket->event->title }}</div>
                    </div>
                @endif
            </div>

            <div class="bg-card border rounded-xl p-5 shadow-sm space-y-4">
                <h3 class="font-bold text-[#1E0A3C] border-b pb-2">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex gap-3">
                        <div class="size-2 rounded-full bg-success mt-1.5"></div>
                        <div>
                            <div class="font-bold text-[10px] uppercase">Created</div>
                            <div class="text-[10px] text-muted-foreground">{{ $ticket->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="size-2 rounded-full bg-primary mt-1.5"></div>
                        <div>
                            <div class="font-bold text-[10px] uppercase">Last Activity</div>
                            <div class="text-[10px] text-muted-foreground">{{ $ticket->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
