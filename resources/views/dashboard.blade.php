@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        
        <!-- Welcome Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-base-content">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="text-base-content/60">Here is what's happening with your events.</p>
            </div>
            @if($mode == 'organizer')
                <a href="{{ route('events.create') }}" class="btn btn-primary">
                    <span class="icon-[tabler--plus] size-5"></span>
                    Create Event
                </a>
            @endif
        </div>

        @if($mode == 'organizer')
            <!-- Organizer / Admin View -->
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Total Revenue -->
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium">Total Revenue</h3>
                        <span class="icon-[lucide--dollar-sign] text-muted-foreground size-4"></span>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">${{ number_format($totalRevenue, 2) }}</div>
                        <p class="text-muted-foreground text-xs mt-1">
                            Lifetime earnings
                        </p>
                    </div>
                </div>

                <!-- Tickets Sold -->
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium">Tickets Sold</h3>
                        <span class="icon-[lucide--ticket] text-muted-foreground size-4"></span>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ number_format($ticketsSold) }}</div>
                        <p class="text-muted-foreground text-xs mt-1">
                            Across all events
                        </p>
                    </div>
                </div>

                <!-- Active Events -->
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium">Total Events</h3>
                        <span class="icon-[lucide--calendar] text-muted-foreground size-4"></span>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $totalEvents }}</div>
                        <p class="text-muted-foreground text-xs mt-1">
                            Created events
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Sales -->
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-col space-y-1.5">
                        <h3 class="font-semibold leading-none tracking-tight">Recent Sales</h3>
                        <p class="text-sm text-muted-foreground">Latest transactions from your events</p>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-border">
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Event</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Buyer</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Amount</th>
                                        <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales as $sale)
                                        <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                                            <td class="p-2 align-middle font-medium truncate max-w-[150px]">{{ $sale->event->title }}</td>
                                            <td class="p-2 align-middle">{{ $sale->user->name }}</td>
                                            <td class="p-2 align-middle font-semibold">${{ number_format($sale->amount, 2) }}</td>
                                            <td class="p-2 align-middle text-xs text-muted-foreground">{{ $sale->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-4 text-center text-muted-foreground opacity-50">No sales yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Events -->
                 <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-col space-y-1.5">
                        <h3 class="font-semibold leading-none tracking-tight">Top Performers</h3>
                        <p class="text-sm text-muted-foreground">Your most popular events by ticket sales</p>
                    </div>
                    <div class="p-6 pt-0">
                        <ul class="flex flex-col gap-4">
                            @forelse($topEvents as $event)
                                <li class="flex items-center justify-between border-b border-border pb-2 last:border-0 hover:bg-muted/50 p-2 rounded-md transition">
                                    <div class="flex items-center gap-3">
                                        @if($event->cover_image_path)
                                            <img src="{{ asset('storage/' . $event->cover_image_path) }}" class="h-10 w-10 rounded object-cover ring-1 ring-border">
                                        @else
                                            <div class="bg-muted text-muted-foreground h-10 w-10 rounded flex items-center justify-center text-xs">IMG</div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-sm">{{ $event->title }}</p>
                                            <p class="text-xs text-muted-foreground">{{ $event->start_date->format('M d') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-sm">{{ $event->tickets_count }} tickets</p>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-muted-foreground opacity-50">No events found.</li>
                            @endforelse
                        </ul>
                    </div>
                 </div>
            </div>

        @elseif($mode == 'scanner')
            <!-- Scanner Dashboard -->
            <div class="grid grid-cols-1 gap-6">
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-col space-y-1.5">
                        <h3 class="font-semibold leading-none tracking-tight">Assigned Events</h3>
                        <p class="text-sm text-muted-foreground">Select an event to start scanning tickets.</p>
                    </div>
                    <div class="p-6 pt-0">
                        @if(count($assignedEvents) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($assignedEvents as $event)
                                    <div class="flex flex-col rounded-lg border border-border bg-background p-4 shadow-sm transition hover:shadow-md">
                                        <div class="mb-4">
                                            <h4 class="font-bold text-lg truncate" title="{{ $event->title }}">{{ $event->title }}</h4>
                                            <div class="flex items-center gap-2 text-sm text-muted-foreground mt-1">
                                                <span class="icon-[tabler--calendar] size-4"></span>
                                                <span>{{ $event->start_date->format('M d, Y h:i A') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-muted-foreground mt-1">
                                                <span class="icon-[tabler--map-pin] size-4"></span>
                                                <span>{{ $event->venue_address ?? 'Online' }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-auto pt-4">
                                            <a href="{{ route('scanner', ['event_id' => $event->id]) }}" class="btn btn-primary w-full shadow-sm group">
                                                <span class="icon-[tabler--scan] size-5 mr-2 group-hover:scale-110 transition-transform"></span>
                                                Scan Tickets
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <span class="icon-[tabler--calendar-off] size-12 text-muted-foreground opacity-50 mb-4"></span>
                                <h3 class="font-semibold text-lg text-foreground">No Assigned Events</h3>
                                <p class="text-muted-foreground max-w-sm mt-1">You haven't been assigned to any events as a scanner yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @else
            <!-- Attendee View -->
            <div class="space-y-12 pb-20">
                {{-- Quick Stats --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-card rounded-2xl border border-border p-6 shadow-sm flex items-center gap-6">
                        <div class="size-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                            <span class="icon-[lucide--ticket] size-7"></span>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-foreground">{{ $upcomingTickets->count() }}</div>
                            <div class="text-sm text-muted-foreground font-medium">Upcoming Tickets</div>
                        </div>
                    </div>
                    <div class="bg-card rounded-2xl border border-border p-6 shadow-sm flex items-center gap-6">
                        <div class="size-14 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                            <span class="icon-[lucide--history] size-7"></span>
                        </div>
                        <div>
                            <div class="text-3xl font-black text-foreground">{{ $pastEventsCount }}</div>
                            <div class="text-sm text-muted-foreground font-medium">Past Events</div>
                        </div>
                    </div>
                    @if($promoterStats)
                        <div class="bg-card rounded-2xl border border-border p-6 shadow-sm flex items-center gap-6">
                            <div class="size-14 rounded-xl bg-success/10 flex items-center justify-center text-success">
                                <span class="icon-[lucide--trending-up] size-7"></span>
                            </div>
                            <div>
                                <div class="text-3xl font-black text-foreground">${{ number_format($promoterStats['total_commission'], 2) }}</div>
                                <div class="text-sm text-muted-foreground font-medium">Total Commissions</div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- My Next Event --}}
                @if($upcomingTickets->isNotEmpty())
                    <section>
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-bold tracking-tight text-foreground">Your Next Events</h3>
                            <a href="{{ route('tickets.index') }}" class="text-sm font-bold text-primary hover:underline">View all tickets</a>
                        </div>
                        <div class="space-y-4">
                            @foreach($upcomingTickets as $ticket)
                                <div class="group bg-card rounded-2xl border border-border p-4 flex flex-col md:flex-row items-center gap-6 hover:border-primary/50 transition-all hover:shadow-lg">
                                    <div class="w-full md:w-48 aspect-video rounded-xl overflow-hidden shadow-inner">
                                        <img src="{{ $ticket->event->cover_image_path ? asset('storage/' . $ticket->event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0 py-2">
                                        <div class="text-primary font-bold text-xs uppercase tracking-widest mb-1">{{ $ticket->event->start_date->format('D, M d • h:i A') }}</div>
                                        <h4 class="text-xl font-bold text-foreground truncate group-hover:text-primary transition-colors">{{ $ticket->event->title }}</h4>
                                        <div class="flex items-center gap-1 text-sm text-muted-foreground mt-2">
                                            <span class="icon-[lucide--map-pin] size-4"></span>
                                            <span>{{ $ticket->event->venue?->name ?? $ticket->event->venue_address ?? 'Location TBA' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('tickets.download', $ticket) }}" class="btn btn-primary shadow-lg shadow-primary/20">
                                            <span class="icon-[lucide--download] size-4 mr-2"></span>
                                            Get Pass
                                        </a>
                                        <a href="{{ route('events.show.public', $ticket->event->slug) }}" class="btn btn-ghost">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Promoter Hub --}}
                @if($promoterStats)
                    <section class="bg-[#1E0A3C] rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl">
                        <div class="absolute -right-20 -top-20 size-80 bg-primary/20 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <div class="flex flex-col lg:flex-row gap-12 items-center">
                                <div class="flex-1 space-y-6 text-center lg:text-left">
                                    <h3 class="text-4xl font-black tracking-tight">Promoter Hub</h3>
                                    <p class="text-white/70 max-w-lg text-lg">Share your passion for events and earn money. Your unique referral link is ready!</p>
                                    
                                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                        <label class="text-[10px] font-bold uppercase tracking-widest text-white/50 mb-3 block">Your Referral Link</label>
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <input type="text" readonly value="{{ url('/?ref=' . $promoterStats['referral_code'] ) }}" class="bg-black/20 border-white/10 text-white rounded-xl focus:ring-primary w-full px-4 h-12 font-medium">
                                            <button onclick="copyToClipboard('{{ url('/?ref=' . $promoterStats['referral_code'] ) }}')" class="btn btn-primary h-12 px-8">
                                                Copy Link
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="bg-white/5 rounded-3xl p-8 border border-white/10 text-center min-w-[180px]">
                                        <div class="text-4xl font-black mb-2">{{ $promoterStats['total_referrals'] }}</div>
                                        <div class="text-[10px] font-bold uppercase text-white/40 tracking-widest">Referrals</div>
                                    </div>
                                    <div class="bg-primary rounded-3xl p-8 text-center min-w-[180px] shadow-lg shadow-primary/30">
                                        <div class="text-4xl font-black mb-2">${{ number_format($promoterStats['total_commission'], 2) }}</div>
                                        <div class="text-[10px] font-bold uppercase text-white/80 tracking-widest">Commission</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Recommended Events --}}
                <section>
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-black tracking-tight text-foreground uppercase italic underline decoration-primary decoration-4 underline-offset-8">Recommended For You</h3>
                        <a href="{{ route('home') }}" class="text-sm font-bold text-primary hover:underline uppercase tracking-widest">Explore More</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($recommendedEvents as $event)
                            <div class="group h-full flex flex-col hover:shadow-xl transition-all duration-300 rounded-lg overflow-hidden border border-transparent hover:border-border bg-card">
                                {{-- Image Container --}}
                                <a href="{{ route('events.show.public', $event->slug) }}" class="relative aspect-[16/9] overflow-hidden rounded-lg">
                                    <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $event->title }}">
                                </a>

                                {{-- Content --}}
                                <div class="pt-4 flex flex-col flex-1 px-1">
                                    <h3 class="font-bold text-lg text-[#1E0A3C] mb-1 line-clamp-2 leading-snug group-hover:text-[#3659E3] transition-colors">
                                        <a href="{{ route('events.show.public', $event->slug) }}">{{ $event->title }}</a>
                                    </h3>
                                    <div class="text-sm font-bold text-[#D1410C] mb-1 uppercase tracking-tight">{{ $event->start_date->format('D, M d • h:i A T') }}</div>
                                    
                                    <div class="text-sm text-muted-foreground mb-4">
                                        {{ $event->venue?->name ?? $event->venue_address ?? 'Location TBA' }}
                                    </div>

                                    <div class="mt-auto">
                                        <div class="text-sm font-bold text-[#1E0A3C]">
                                            @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                            {{ $minPrice > 0 ? 'From $' . number_format($minPrice, 2) : 'Free' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            @push('scripts')
            <script>
                function copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        const btn = event.target.closest('button');
                        const originalText = btn.innerText;
                        btn.innerText = 'Copied!';
                        btn.className = btn.className.replace('btn-primary', 'btn-success');
                        setTimeout(() => {
                            btn.innerText = originalText;
                            btn.className = btn.className.replace('btn-success', 'btn-primary');
                        }, 2000);
                    });
                }
            </script>
            @endpush
        @endif
    </div>
@endsection
