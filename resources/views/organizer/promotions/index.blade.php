@extends('layouts.app')

@section('title', 'My Promotions')

@section('content')
<div class="mx-auto max-w-6xl space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-foreground">My Promotions</h2>
            <p class="text-muted-foreground mt-1 text-lg">Boost your event visibility with premium ad packages.</p>
        </div>
        <a href="{{ route('organizer.promotions.create') }}" class="btn btn-primary shadow-lg shadow-primary/25 rounded-full px-6 transition-transform active:scale-95">
            <span class="icon-[tabler--bolt] mr-2 size-5"></span>
            Promote New Event
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-success/20">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Dashboard Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Balance Card -->
        <div class="card bg-gradient-to-br from-primary to-primary/80 text-primary-foreground shadow-xl relative overflow-hidden">
            <div class="absolute -right-8 -top-8 bg-white/10 rounded-full size-40 blur-2xl"></div>
            <div class="card-body p-6 relative z-10">
                <div class="flex justify-between items-start">
                    <h3 class="font-bold opacity-90 text-sm tracking-wide uppercase">Wallet Balance</h3>
                    <span class="icon-[tabler--wallet] size-6 opacity-70"></span>
                </div>
                <div class="mt-4 mb-2">
                    <div class="text-4xl font-black tracking-tighter">${{ number_format(auth()->user()->credits, 2) }}</div>
                </div>
                <div class="card-actions mt-auto">
                    <a href="{{ route('organizer.credits.index') }}" class="btn btn-sm btn-white text-primary font-bold border-none hover:bg-white/90 w-full">
                        <span class="icon-[tabler--plus] mr-1 size-4"></span> Top Up
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="card bg-card border border-border shadow-sm md:col-span-2">
            <div class="card-body p-6">
                <h3 class="card-title text-base font-bold text-muted-foreground uppercase tracking-wide mb-6">Performance Overview</h3>
                <div class="grid grid-cols-2 gap-8">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-full bg-success/10 flex items-center justify-center text-success">
                            <span class="icon-[tabler--ad] size-6"></span>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-foreground">{{ auth()->user()->promotions()->active()->count() }}</div>
                            <div class="text-sm font-medium text-muted-foreground">Active Promotions</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                            <span class="icon-[tabler--chart-pie] size-6"></span>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-foreground">${{ number_format(auth()->user()->promotions()->sum('cost'), 2) }}</div>
                            <div class="text-sm font-medium text-muted-foreground">Total Invested</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions List -->
    <div class="card bg-card border border-border shadow-sm overflow-hidden">
        
        @if($promotions->count() > 0)
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-muted/40 border-b border-border">
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground w-[40%]">Event Campaign</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground">Package</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground">Duration</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground">Status</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground text-right">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($promotions as $promo)
                            <tr class="group hover:bg-muted/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="avatar rounded-xl shrink-0 shadow-sm border border-border overflow-hidden">
                                            <div class="w-14 h-14 bg-muted">
                                                <img src="{{ $promo->event->cover_image_path ? asset('storage/'.$promo->event->cover_image_path) : 'https://placehold.co/100' }}" 
                                                     alt="Event Cover" 
                                                     class="object-cover w-full h-full transition-transform group-hover:scale-105" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold text-foreground text-base line-clamp-1 group-hover:text-primary transition-colors">
                                                {{ $promo->event->title }}
                                            </div>
                                            <div class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                                                <span class="icon-[tabler--calendar] size-3"></span>
                                                {{ $promo->start_date->format('M d') }} - {{ $promo->end_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="badge badge-soft text-xs font-bold border-0 bg-primary/10 text-primary">
                                        {{ $promo->package->name ?? 'Custom' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <span class="font-mono text-xs font-medium bg-muted px-2 py-1 rounded">
                                        {{ $promo->start_date->diffInDays($promo->end_date) }} Days
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    @php
                                        $statusClass = match($promo->status) {
                                            'active' => 'badge-success text-success-foreground shadow-sm shadow-success/20',
                                            'pending' => 'badge-warning text-warning-foreground',
                                            'ended' => 'bg-muted text-muted-foreground border-border',
                                            'rejected' => 'badge-error text-error-foreground',
                                            'paused' => 'badge-warning text-warning-foreground',
                                            default => 'badge-ghost',
                                        };
                                        $icon = match($promo->status) {
                                            'active' => 'check',
                                            'pending' => 'clock',
                                            'ended' => 'archive',
                                            'rejected' => 'x',
                                            'paused' => 'player-pause',
                                            default => 'circle',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} gap-1.5 py-2">
                                        <span class="icon-[tabler--{{ $icon }}] size-3.5"></span>
                                        {{ ucfirst($promo->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right align-middle">
                                    <span class="font-bold text-foreground tracking-tight">${{ number_format($promo->cost, 2) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border bg-muted/5">
                {{ $promotions->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="bg-primary/5 p-6 rounded-full mb-6 relative">
                    <div class="absolute inset-0 bg-primary/10 rounded-full blur-xl animate-pulse"></div>
                    <span class="icon-[tabler--rocket] size-12 text-primary relative z-10"></span>
                </div>
                <h3 class="text-xl font-bold text-foreground">No Promotions Yet</h3>
                <p class="text-muted-foreground max-w-sm mt-2 mb-8">Ready to get more eyes on your events? Start your first campaign today.</p>
                <a href="{{ route('organizer.promotions.create') }}" class="btn btn-primary shadow-lg shadow-primary/20">
                    Promote Your First Event
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
