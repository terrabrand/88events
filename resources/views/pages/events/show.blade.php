@extends('layouts.public')

@section('title', $event->title)

@section('public-content')
<div class="bg-background pb-20">
    {{-- Hero Image --}}
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="aspect-[21/9] w-full rounded-2xl overflow-hidden shadow-2xl border border-border bg-card">
            <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
                 class="w-full h-full object-cover" alt="{{ $event->title }}">
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 -mt-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            {{-- Main Content (Left) --}}
            <div class="lg:col-span-2 pt-8">
                
                {{-- Date Warning --}}
                <div class="mb-6 font-bold text-primary text-lg">
                    {{ $event->start_date->format('l, F d') }}
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-foreground mb-6 leading-tight">{{ $event->title }}</h1>
                
                <div class="flex items-center gap-4 mb-8 pb-8 border-b border-border">
                    <div class="avatar placeholder">
                        <div class="bg-muted text-muted-foreground w-12 rounded-full flex items-center justify-center">
                            <span class="text-xl font-bold">{{ substr($event->organizer->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="font-bold text-foreground">By {{ $event->organizer->name }}</div>
                        <div class="text-sm text-muted-foreground">{{ number_format(rand(100, 5000)) }} followers</div>
                    </div>
                    <button class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">Follow</button>
                </div>

                <section class="mb-12">
                     <h2 class="text-2xl font-bold mb-6 text-foreground">Overview</h2>
                     <p class="text-lg text-muted-foreground italic mb-6">
                        "{{ \Illuminate\Support\Str::limit($event->description, 100) }}"
                     </p>
                     
                     <div class="prose prose-lg max-w-none text-muted-foreground">
                        {!! nl2br(e($event->description)) !!}
                     </div>
                </section>

                <section class="mb-12">
                    <h2 class="text-2xl font-bold mb-6 text-foreground">Good to know</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-card text-card-foreground p-6 rounded-xl border border-border shadow-sm">
                            <h3 class="font-bold mb-4">Highlights</h3>
                            <ul class="space-y-3">
                                <li class="flex items-center gap-3 text-muted-foreground">
                                    <span class="icon-[lucide--clock] size-5"></span>
                                    Duration: {{ $event->end_date ? $event->start_date->diffInHours($event->end_date) . ' hours' : 'TBA' }}
                                </li>
                                <li class="flex items-center gap-3 text-muted-foreground">
                                    <span class="icon-[lucide--map-pin] size-5"></span>
                                    {{ $event->venue ? $event->venue->name : 'Online Event' }}
                                </li>
                            </ul>
                        </div>
                        <div class="bg-card text-card-foreground p-6 rounded-xl border border-border shadow-sm">
                            <h3 class="font-bold mb-4">Refund Policy</h3>
                            <p class="text-muted-foreground text-sm">Contact the organizer to request a refund. Eventbrite's fee is nonrefundable.</p>
                        </div>
                    </div>
                </section>

                <section class="mb-12">
                    <h2 class="text-2xl font-bold mb-6 text-foreground">Tags</h2>
                    <div class="flex flex-wrap gap-2">
                        <span class="badge badge-lg bg-muted text-muted-foreground border-transparent px-4 py-3">Canada Events</span>
                        <span class="badge badge-lg bg-muted text-muted-foreground border-transparent px-4 py-3">Online Events</span>
                        <span class="badge badge-lg bg-muted text-muted-foreground border-transparent px-4 py-3">Things to do</span>
                        <span class="badge badge-lg bg-muted text-muted-foreground border-transparent px-4 py-3">{{ $event->category->name ?? 'Event' }}</span>
                    </div>
                </section>

                <section class="mb-12 pb-12 border-b border-border">
                    <h2 class="text-2xl font-bold mb-6 text-foreground">Organized by</h2>
                    <div class="bg-card text-card-foreground border border-border rounded-xl p-8 flex flex-col md:flex-row items-center gap-8 text-center md:text-left shadow-sm">
                         <div class="avatar placeholder">
                            <div class="bg-muted text-muted-foreground w-24 rounded-full flex items-center justify-center">
                                <span class="text-4xl font-bold">{{ substr($event->organizer->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-1">{{ $event->organizer->name }}</h3>
                            <div class="flex items-center justify-center md:justify-start gap-4 text-sm text-muted-foreground mb-6">
                                <span>{{ number_format(rand(100, 5000)) }} Followers</span>
                                <span class="w-1 h-1 rounded-full bg-muted-foreground"></span>
                                <span>{{ \App\Models\Event::where('organizer_id', $event->organizer_id)->count() }} Events</span>
                            </div>
                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <button class="rounded-full bg-primary px-8 py-2 text-sm font-bold text-primary-foreground shadow-sm hover:bg-primary/90 transition-all">Follow</button>
                                <button class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">Contact</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Sidebar (Right) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                     <div class="bg-card text-card-foreground rounded-xl shadow-lg border border-border overflow-hidden p-6 text-center">
                        <div class="mb-2 text-sm font-medium text-muted-foreground">Price</div>
                        <div class="text-3xl font-black text-foreground mb-6">
                            {{ $event->ticketTypes->min('price') > 0 ? 'From $' . number_format($event->ticketTypes->min('price'), 2) : 'Free' }}
                        </div>
                        
                        <button
                            type="button"
                            onclick="document.getElementById('ticket-modal').showModal()"
                            class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">
                            Check availability
                            <span class="icon-[lucide--arrow-right] size-5"></span>
                        </button>

                     </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Ticket Modal --}}
    <dialog id="ticket-modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
        <form method="dialog" class="fixed inset-0 outline-none w-full h-full cursor-pointer" tabindex="-1"></form>
        <div class="modal-box w-full max-w-2xl bg-card text-card-foreground border border-border shadow-2xl rounded-2xl p-0 overflow-hidden relative z-10 mx-4 shadow-sm" onclick="event.stopPropagation()">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-border bg-muted/20 flex items-center justify-between sticky top-0 z-10">
                <h3 class="font-black text-xl md:text-2xl text-foreground tracking-tight">Select Tickets</h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                        <span class="icon-[lucide--x] size-5"></span>
                    </button>
                </form>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[70vh] custom-scrollbar">
                <form action="{{ route('tickets.purchase', $event) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        @forelse($event->ticketTypes as $type)
                            <label class="relative flex items-start p-4 rounded-xl border border-border bg-background hover:border-primary/50 cursor-pointer transition-all hover:shadow-sm group">
                                <div class="pt-1">
                                    <input type="radio" name="ticket_type_id" value="{{ $type->id }}" class="radio radio-primary radio-sm my-auto" {{ $loop->first ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1 ml-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold text-foreground text-lg group-hover:text-primary transition-colors">{{ $type->name }}</span>
                                        <span class="font-black text-lg text-primary">{{ $type->price > 0 ? '$' . number_format($type->price, 2) : 'Free' }}</span>
                                    </div>
                                    <div class="text-sm text-muted-foreground leading-relaxed">
                                        {{ $type->description ?? 'General admission access to the event.' }}
                                    </div>
                                    <div class="mt-3 flex items-center gap-2">
                                        <span class="badge badge-sm badge-outline text-muted-foreground border-border">
                                            {{ $type->quantity > 0 ? $type->quantity . ' remaining' : 'Sold out' }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-12 rounded-xl border-2 border-dashed border-border bg-muted/20">
                                <span class="icon-[lucide--ticket] size-12 text-muted-foreground/50 mb-3"></span>
                                <p class="text-muted-foreground font-medium">No tickets available at the moment.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($event->ticketTypes->count() > 0)
                        <div class="pt-6 border-t border-border flex flex-col sm:flex-row items-end sm:items-center justify-between gap-4">
                            <div class="w-full sm:w-1/3">
                                <label class="text-xs font-bold text-muted-foreground uppercase mb-2 block tracking-wider">Quantity</label>
                                <select name="quantity" class="select select-bordered w-full bg-background text-foreground border-input focus:border-primary focus:ring-primary h-11 text-base">
                                    @foreach(range(1, 10) as $q)
                                        <option value="{{ $q }}">{{ $q }} Ticket{{ $q > 1 ? 's' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-1/2">
                                 @guest
                                    <p class="text-xs text-muted-foreground mb-2 text-right">
                                        Account required
                                    </p>
                                    <a href="{{ route('login') }}" class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">
                                        Login to Purchase
                                    </a>
                                @else
                                    <button type="submit" class="btn btn-primary w-full text-primary-foreground font-bold shadow-lg shadow-primary/20 h-11 text-lg">
                                        Proceed to Checkout
                                        <span class="icon-[lucide--arrow-right] size-4 ml-2"></span>
                                    </button>
                                @endguest
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </dialog>
</div>
@endsection
