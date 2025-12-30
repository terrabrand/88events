@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-foreground">SMS Marketing</h2>
            <p class="text-muted-foreground mt-1">Send reminders and marketing SMS to your event attendees.</p>
        </div>
    </div>

    <!-- Subscription Status Card -->
    <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
        <div class="p-6 bg-primary/5 border-b border-border">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-primary/10 text-primary">
                        <span class="icon-[tabler--credit-card] size-6"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Subscription Plan</h3>
                        <p class="text-sm text-muted-foreground">Manage your SMS limits and usage.</p>
                    </div>
                </div>
                @if($subscription)
                    <span class="inline-flex items-center rounded-full bg-success/15 px-3 py-1 text-xs font-bold text-success uppercase tracking-wider">
                        Active Plan: {{ $subscription->package->name }}
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-destructive/15 px-3 py-1 text-xs font-bold text-destructive uppercase tracking-wider">
                        No Active Plan
                    </span>
                @endif
            </div>
        </div>
        <div class="p-6">
            @if($subscription)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground font-medium">SMS Usage</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold">{{ $subscription->sms_used }}</span>
                            <span class="text-muted-foreground">/ {{ $subscription->package->sms_limit }}</span>
                        </div>
                        <div class="h-2 w-full bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-primary" style="width: {{ ($subscription->sms_used / $subscription->package->sms_limit) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground font-medium">Remaining Credits</p>
                        <p class="text-3xl font-bold text-primary">{{ $subscription->package->sms_limit - $subscription->sms_used }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground font-medium">Expires On</p>
                        <p class="text-xl font-semibold">{{ $subscription->ends_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @else
                <div class="text-center py-6 space-y-4">
                    <div class="max-w-md mx-auto">
                        <p class="text-muted-foreground mb-4">You need an active SMS package to start sending marketing messages and reminders to your attendees.</p>
                        <a href="#" class="btn btn-primary">Choose a Package</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Events List for SMS -->
    <div class="space-y-4">
        <h3 class="text-xl font-bold">Select Event</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="group relative rounded-xl border border-border bg-card transition-all hover:shadow-md hover:border-primary/50 overflow-hidden">
                    @if($event->cover_image_path)
                        <img src="{{ asset('storage/'.$event->cover_image_path) }}" alt="{{ $event->title }}" class="h-40 w-full object-cover">
                    @else
                        <div class="h-40 w-full bg-muted flex items-center justify-center">
                            <span class="icon-[tabler--photo] size-12 text-muted-foreground/30"></span>
                        </div>
                    @endif
                    
                    <div class="p-5 space-y-3">
                        <div>
                            <span class="text-xs font-bold text-primary uppercase tracking-widest">{{ $event->category->name ?? 'Event' }}</span>
                            <h4 class="font-bold text-lg mt-1 group-hover:text-primary transition-colors line-clamp-1">{{ $event->title }}</h4>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <span class="icon-[tabler--users] size-4"></span>
                            <span>{{ $event->tickets_count }} Attendees</span>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('organizer.sms.create', $event) }}" class="btn btn-outline btn-sm w-full gap-2">
                                <span class="icon-[tabler--message-share] size-4"></span>
                                Send SMS
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($events->isEmpty())
             <div class="rounded-xl border-2 border-dashed border-border p-12 text-center">
                <span class="icon-[tabler--calendar-off] size-12 text-muted-foreground/30 mb-4 inline-block"></span>
                <p class="text-muted-foreground">You don't have any events yet. Create an event first to start marketing.</p>
            </div>
        @endif
    </div>
</div>
@endsection
