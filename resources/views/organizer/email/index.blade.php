@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Email Marketing</h2>
            <p class="text-muted-foreground mt-1">Send beautiful email campaigns and reminders to your attendees.</p>
        </div>
    </div>

    <!-- Subscription Status Card -->
    <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
        <div class="p-6 bg-primary/5 border-b border-border">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-primary/10 text-primary">
                        <span class="icon-[tabler--mail-forward] size-6"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Email Subscription</h3>
                        <p class="text-sm text-muted-foreground">Manage your Email limits and usage.</p>
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
                        <p class="text-sm text-muted-foreground font-medium">Email Usage</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold">{{ $subscription->email_used }}</span>
                            <span class="text-muted-foreground">/ {{ $subscription->package->email_limit }}</span>
                        </div>
                        <div class="h-2 w-full bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-primary" style="width: {{ ($subscription->email_used / max(1, $subscription->package->email_limit)) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground font-medium">Remaining Emails</p>
                        <p class="text-3xl font-bold text-primary">{{ $subscription->package->email_limit - $subscription->email_used }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground font-medium">Expires On</p>
                        <p class="text-xl font-semibold">{{ $subscription->ends_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @else
                <div class="text-center py-6 space-y-4">
                    <div class="max-w-md mx-auto">
                        <p class="text-muted-foreground mb-4">You need an active package to start sending email marketing campaigns through Mailchimp.</p>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-primary">Browse Plans</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Events List -->
    <div class="space-y-4">
        <h3 class="text-xl font-bold">Launch Email Campaign</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="group relative rounded-xl border border-border bg-card transition-all hover:shadow-md hover:border-primary/50 overflow-hidden">
                    @if($event->cover_image_path)
                        <img src="{{ asset('storage/'.$event->cover_image_path) }}" alt="{{ $event->title }}" class="h-32 w-full object-cover">
                    @else
                        <div class="h-32 w-full bg-muted flex items-center justify-center">
                            <span class="icon-[tabler--mail] size-8 text-muted-foreground/30"></span>
                        </div>
                    @endif
                    
                    <div class="p-5 space-y-3">
                        <h4 class="font-bold text-lg group-hover:text-primary transition-colors line-clamp-1">{{ $event->title }}</h4>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <span class="icon-[tabler--users] size-4"></span>
                            <span>{{ $event->tickets_count }} Registered Attendees</span>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('organizer.email.create', $event) }}" class="btn btn-primary btn-sm w-full gap-2">
                                <span class="icon-[tabler--pencil-plus] size-4"></span>
                                Compose Email
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
