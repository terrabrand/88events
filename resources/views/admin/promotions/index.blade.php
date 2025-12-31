@extends('layouts.app')

@section('title', 'Manage Promotions')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-foreground">Ad Promotions</h2>
            <p class="text-muted-foreground mt-1 text-lg">Review and manage organizer event promotions.</p>
        </div>
        <div class="flex gap-2">
            <!-- Filter placeholder, could be expanded later -->
            <button class="btn btn-ghost hover:bg-muted text-muted-foreground hover:text-foreground">
                <span class="icon-[tabler--filter] size-5 mr-2"></span>
                Filter
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-success/20">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <div class="card bg-card border border-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-muted/40 border-b border-border">
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground w-[30%]">Event Details</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground">Organizer</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground">Package Info</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground">Schedule</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground">Status</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-muted-foreground text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($promotions as $promo)
                        <tr class="group hover:bg-muted/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <div class="avatar rounded-xl shrink-0 shadow-sm border border-border overflow-hidden">
                                        <div class="w-14 h-14 bg-muted">
                                            <img src="{{ $promo->event->cover_image_path ? asset('storage/'.$promo->event->cover_image_path) : 'https://placehold.co/100' }}" 
                                                 alt="Event Cover" 
                                                 class="object-cover w-full h-full transition-transform group-hover:scale-105" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-foreground text-base line-clamp-1" title="{{ $promo->event->title }}">
                                            {{ $promo->event->title }}
                                        </div>
                                        <div class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                                            <span class="icon-[tabler--hash] size-3"></span>
                                            {{ $promo->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-semibold text-foreground">{{ $promo->user->name }}</div>
                                <div class="text-xs text-muted-foreground mt-0.5">{{ $promo->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="badge badge-soft text-xs font-bold border-0 bg-primary/10 text-primary">
                                    {{ $promo->package->name ?? 'Custom' }}
                                </div>
                                <div class="text-xs mt-1.5 font-mono text-muted-foreground">
                                    ${{ number_format($promo->cost, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-sm space-y-1">
                                    <div class="flex items-center gap-2 text-foreground/80">
                                        <span class="icon-[tabler--calendar-start] size-3.5 text-muted-foreground"></span>
                                        {{ $promo->start_date->format('M d, Y') }}
                                    </div>
                                    <div class="flex items-center gap-2 text-muted-foreground text-xs">
                                        <span class="icon-[tabler--calendar-due] size-3.5 opacity-70"></span>
                                        {{ $promo->end_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                @php
                                    $statusColor = match($promo->status) {
                                        'active' => 'badge-success text-success-foreground shadow-sm shadow-success/20',
                                        'pending' => 'badge-warning text-warning-foreground shadow-sm shadow-warning/20',
                                        'ended' => 'bg-muted text-muted-foreground border-border',
                                        'rejected' => 'badge-error text-error-foreground shadow-sm shadow-error/20',
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
                                <span class="badge {{ $statusColor }} gap-1.5 py-3 px-3">
                                    <span class="icon-[tabler--{{ $icon }}] size-3.5"></span>
                                    {{ ucfirst($promo->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right align-middle">
                                <div class="dropdown dropdown-end">
                                    <div tabindex="0" role="button" class="btn btn-ghost btn-sm btn-square text-muted-foreground hover:text-foreground hover:bg-muted">
                                        <span class="icon-[tabler--dots-vertical] size-5"></span>
                                    </div>
                                    <ul tabindex="0" class="dropdown-content z-[10] menu p-1.5 shadow-lg bg-card rounded-xl w-52 border border-border">
                                        <li class="menu-title text-xs font-medium text-muted-foreground px-3 py-2">Change Status</li>
                                        @if($promo->status === 'pending')
                                            <li>
                                                <form action="{{ route('admin.promotions.status', $promo) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="active">
                                                    <button class="text-success hover:bg-success/10 rounded-lg"><span class="icon-[tabler--check] mr-2"></span>Approve</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.promotions.status', $promo) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button class="text-error hover:bg-error/10 rounded-lg"><span class="icon-[tabler--x] mr-2"></span>Reject</button>
                                                </form>
                                            </li>
                                        @elseif($promo->status === 'active')
                                            <li>
                                                <form action="{{ route('admin.promotions.status', $promo) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="paused">
                                                    <button class="text-warning hover:bg-warning/10 rounded-lg"><span class="icon-[tabler--player-pause] mr-2"></span>Pause</button>
                                                </form>
                                            </li>
                                        @elseif($promo->status === 'paused')
                                            <li>
                                                <form action="{{ route('admin.promotions.status', $promo) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="active">
                                                    <button class="text-success hover:bg-success/10 rounded-lg"><span class="icon-[tabler--player-play] mr-2"></span>Resume</button>
                                                </form>
                                            </li>
                                        @endif
                                        <li class="divider my-1"></li>
                                        <li><a href="{{ route('events.show', $promo->event) }}" target="_blank" class="rounded-lg"><span class="icon-[tabler--external-link] mr-2"></span>View Event</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-muted/30 p-4 rounded-full mb-3">
                                        <span class="icon-[tabler--megaphone-off] size-8 text-muted-foreground"></span>
                                    </div>
                                    <h3 class="text-lg font-bold text-foreground">No Promotions Found</h3>
                                    <p class="text-muted-foreground max-w-xs mt-1">There are no event promotions to display at this time.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border bg-muted/5">
            {{ $promotions->links() }}
        </div>
    </div>
</div>
@endsection
