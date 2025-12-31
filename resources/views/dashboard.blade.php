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
            <div class="space-y-10 pb-20">
                {{-- Hero Greeting --}}
                <div class="bg-gradient-to-br from-[#1E0A3C] via-[#3659E3] to-[#D1410C] rounded-3xl p-8 md:p-12 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 size-80 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 space-y-4">
                        <h1 class="text-4xl md:text-5xl font-black tracking-tight">Ready for your next adventure?</h1>
                        <p class="text-white/80 text-lg max-w-xl">Find all your tickets, manage your promoter stats, and discover events tailored just for you.</p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('home') }}" class="btn bg-white text-black border-none hover:bg-gray-100 rounded-full px-8 shadow-lg">Browse Events</a>
                            <a href="{{ route('tickets.index') }}" class="btn btn-outline border-white/30 text-white hover:bg-white/10 rounded-full px-8 backdrop-blur-sm">My Tickets</a>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-card rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="flex items-center gap-4">
                            <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                <span class="icon-[lucide--ticket] size-6"></span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-muted-foreground uppercase tracking-widest">Upcoming</div>
                                <div class="text-3xl font-black text-[#1E0A3C]">{{ $upcomingTickets->count() }} Tickets</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-card rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="flex items-center gap-4">
                            <div class="size-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-all">
                                <span class="icon-[lucide--history] size-6"></span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-muted-foreground uppercase tracking-widest">Attended</div>
                                <div class="text-3xl font-black text-[#1E0A3C]">{{ $pastEventsCount }} Events</div>
                            </div>
                        </div>
                    </div>
                    @if($promoterStats)
                        <div class="bg-card rounded-2xl border border-border/50 p-6 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center gap-4">
                                <div class="size-12 rounded-xl bg-success/10 flex items-center justify-center text-success group-hover:bg-success group-hover:text-white transition-all">
                                    <span class="icon-[lucide--banknote] size-6"></span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-muted-foreground uppercase tracking-widest">Earnings</div>
                                    <div class="text-3xl font-black text-[#1E0A3C]">${{ number_format($promoterStats['total_commission'], 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    {{-- Upcoming Events List --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-black text-[#1E0A3C] tracking-tight">Your Next Events</h3>
                            <a href="{{ route('tickets.index') }}" class="text-xs font-bold text-primary hover:underline">View All</a>
                        </div>
                        
                        @if($upcomingTickets->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($upcomingTickets as $ticket)
                                    <div class="bg-card border border-border/60 rounded-2xl overflow-hidden flex items-stretch group hover:border-primary/40 transition-all hover:shadow-lg">
                                        <div class="w-32 md:w-48 shrink-0 relative overflow-hidden">
                                            <img src="{{ $ticket->event->cover_image_path ? asset('storage/' . $ticket->event->cover_image_path) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070' }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                        </div>
                                        <div class="flex-1 p-5 flex flex-col justify-center min-w-0">
                                            <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">{{ $ticket->event->start_date->format('D, M d • h:i A') }}</div>
                                            <h4 class="text-lg font-bold text-[#1E0A3C] truncate mb-2">{{ $ticket->event->title }}</h4>
                                            <div class="flex items-center gap-1.5 text-xs text-muted-foreground mb-4">
                                                <span class="icon-[lucide--map-pin] size-3.5 shrink-0"></span>
                                                <span class="truncate">{{ $ticket->event->venue?->name ?? $ticket->event->venue_address ?? 'Location TBA' }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('tickets.download', $ticket) }}" class="btn btn-primary btn-sm rounded-full px-4 shadow-md shadow-primary/20">
                                                    <span class="icon-[lucide--download] size-3.5 mr-1.5"></span> Pass
                                                </a>
                                                <a href="{{ route('events.show.public', $ticket->event->slug) }}" class="btn btn-ghost btn-sm rounded-full px-4 border border-border">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-muted/30 border border-dashed rounded-2xl py-12 flex flex-col items-center text-center">
                                <div class="size-16 rounded-full bg-muted flex items-center justify-center text-muted-foreground mb-4 opacity-50">
                                    <span class="icon-[lucide--calendar] size-8"></span>
                                </div>
                                <h4 class="font-bold text-[#1E0A3C]">No upcoming events</h4>
                                <p class="text-sm text-muted-foreground mt-1 max-w-xs">You haven't booked any tickets for future events yet.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary btn-sm rounded-full mt-6 px-8">Find Events</a>
                            </div>
                        @endif
                    </div>

                    {{-- Sidebar: Promoter Hub or Actions --}}
                    <div class="space-y-6">
                        @if($promoterStats)
                            <div class="bg-card border border-border rounded-3xl p-6 shadow-sm overflow-hidden relative">
                                <div class="absolute top-0 right-0 p-4 opacity-5">
                                    <span class="icon-[lucide--star] size-20"></span>
                                </div>
                                <h4 class="text-xl font-black text-[#1E0A3C] tracking-tight mb-4">Promoter Hub</h4>
                                <div class="space-y-6">
                                    <div class="bg-[#FBF2C4]/30 rounded-2xl p-4 border border-[#FBF2C4]">
                                        <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Referral Code</div>
                                        <div class="text-lg font-black text-[#1E0A3C]">{{ $promoterStats['referral_code'] }}</div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block px-1">Share Link</label>
                                        <div class="flex flex-col gap-2">
                                            <input type="text" readonly value="{{ url('/?ref=' . $promoterStats['referral_code'] ) }}" class="input input-sm border-border bg-muted/50 rounded-xl text-xs focus:ring-primary w-full h-10">
                                            <button onclick="copyToClipboard('{{ url('/?ref=' . $promoterStats['referral_code'] ) }}')" class="btn btn-primary btn-sm rounded-xl h-10 flex-1">
                                                <span class="icon-[lucide--copy] size-3.5 mr-2"></span> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-3 bg-card border rounded-2xl">
                                            <div class="text-xs font-bold text-muted-foreground mb-0.5">Clicks</div>
                                            <div class="text-xl font-black text-[#1E0A3C]">--</div>
                                        </div>
                                        <div class="p-3 bg-primary/5 border border-primary/20 rounded-2xl">
                                            <div class="text-xs font-bold text-primary mb-0.5">Sales</div>
                                            <div class="text-xl font-black text-primary">{{ $promoterStats['total_referrals'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-card border border-border rounded-3xl p-6 shadow-sm space-y-4">
                            <h4 class="text-lg font-bold text-[#1E0A3C]">Quick Actions</h4>
                            <div class="grid grid-cols-1 gap-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-ghost justify-start rounded-xl px-4 hover:bg-muted font-medium text-sm">
                                    <span class="icon-[lucide--user-cog] size-4 mr-3 text-muted-foreground"></span> Edit Profile
                                </a>
                                <a href="{{ route('support.index') }}" class="btn btn-ghost justify-start rounded-xl px-4 hover:bg-muted font-medium text-sm">
                                    <span class="icon-[lucide--life-buoy] size-4 mr-3 text-muted-foreground"></span> Get Support
                                </a>
                                <a href="{{ route('tickets.index') }}" class="btn btn-ghost justify-start rounded-xl px-4 hover:bg-muted font-medium text-sm">
                                    <span class="icon-[lucide--ticket] size-4 mr-3 text-muted-foreground"></span> ticket History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recommended Events --}}
                <section class="space-y-6">
                    <div class="flex items-center justify-between border-b border-border pb-4">
                        <h3 class="text-2xl font-black tracking-tight text-[#1E0A3C] uppercase italic">Recommended For You</h3>
                        <a href="{{ route('home') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                            Explore More <span class="icon-[lucide--arrow-right] size-3"></span>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($recommendedEvents as $event)
                            <div class="group h-full flex flex-col bg-card rounded-2xl overflow-hidden border border-border/50 hover:border-primary/40 hover:shadow-xl transition-all duration-300">
                                <a href="{{ route('events.show.public', $event->slug) }}" class="relative aspect-[16/9] overflow-hidden">
                                    <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070' }}" 
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $event->title }}">
                                    <div class="absolute top-4 right-4">
                                        <div class="bg-white/90 backdrop-blur rounded-full px-3 py-1 text-[10px] font-black text-[#D1410C] shadow-sm uppercase tracking-widest">
                                            {{ $event->start_date->format('M d') }}
                                        </div>
                                    </div>
                                </a>

                                <div class="p-5 flex flex-col flex-1">
                                    <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">{{ $event->category?->name ?? 'Mixed' }}</div>
                                    <h3 class="font-bold text-lg text-[#1E0A3C] mb-2 line-clamp-2 leading-snug group-hover:text-primary transition-colors">
                                        <a href="{{ route('events.show.public', $event->slug) }}">{{ $event->title }}</a>
                                    </h3>
                                    <div class="flex items-center gap-1.5 text-xs text-muted-foreground mb-4">
                                        <span class="icon-[lucide--map-pin] size-3.5"></span>
                                        <span class="truncate">{{ $event->venue?->name ?? $event->venue_address ?? 'Location TBA' }}</span>
                                    </div>
                                    
                                    <div class="mt-auto pt-4 flex items-center justify-between border-t border-border/50">
                                        <div class="text-sm font-black text-[#1E0A3C]">
                                            @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                            {{ $minPrice > 0 ? 'From $' . number_format($minPrice, 2) : 'Free' }}
                                        </div>
                                        <a href="{{ route('events.show.public', $event->slug) }}" class="btn btn-ghost btn-xs rounded-full">Book Now</a>
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
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="icon-[lucide--check] size-3.5 mr-2"></span> Copied!';
                        btn.className = btn.className.replace('btn-primary', 'btn-success');
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.className = btn.className.replace('btn-success', 'btn-primary');
                        }, 2000);
                    });
                }
            </script>
            @endpush
        @endif
    </div>
@endsection
