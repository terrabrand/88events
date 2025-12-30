@extends('layouts.guest')

@section('title', $event->title)

@section('content')
<div class="min-h-screen bg-background pb-20">
    {{-- Hero Section --}}
    <div class="relative h-[400px] md:h-[500px] w-full overflow-hidden">
        <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
            class="w-full h-full object-cover blur-sm brightness-50 scale-105" alt="">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="max-w-4xl w-full bg-card rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-border/40">
                <div class="md:w-2/3 aspect-video md:aspect-auto overflow-hidden">
                    <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
                        class="w-full h-full object-cover" alt="{{ $event->title }}">
                </div>
                <div class="md:w-1/3 p-8 flex flex-col justify-center">
                    <div class="text-primary font-bold mb-2">{{ $event->start_date->format('M d') }}</div>
                    <h1 class="text-3xl font-black tracking-tight text-foreground leading-tight mb-4">{{ $event->title }}</h1>
                    <div class="flex items-center gap-2 text-muted-foreground text-sm">
                        <span class="icon-[lucide--user] size-4"></span>
                        <span>By <span class="font-bold text-foreground">{{ $event->organizer->name }}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-12">
        <div class="flex flex-col lg:flex-row gap-12">
            {{-- Left Column: Details --}}
            <div class="lg:w-2/3 space-y-12">
                <section>
                    <h2 class="text-2xl font-bold mb-6">About this event</h2>
                    <div class="prose prose-invert max-w-none text-muted-foreground leading-relaxed">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-bold mb-6">Location</h2>
                    <div class="flex items-start gap-4">
                        <div class="size-12 rounded-xl bg-muted flex items-center justify-center shrink-0">
                            <span class="icon-[lucide--map-pin] size-6 text-primary"></span>
                        </div>
                        <div>
                            <div class="font-bold text-lg">{{ $event->venue?->name ?? 'Venue Name' }}</div>
                            <div class="text-muted-foreground">{{ $event->venue?->address ?? $event->venue_address }}</div>
                            @if($event->venue?->city)
                                <div class="text-muted-foreground">{{ $event->venue->city }}, {{ $event->venue->state }}</div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            {{-- Right Column: Tickets Sidebar --}}
            <div class="lg:w-1/3">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-card rounded-2xl border border-border p-6 shadow-sm">
                        <h3 class="text-xl font-bold mb-6">Tickets</h3>
                        
                        <form action="{{ route('tickets.purchase', $event) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-3">
                                @forelse($event->ticketTypes as $type)
                                    <label class="relative flex items-center p-4 rounded-xl border border-border hover:border-primary/50 cursor-pointer transition-colors group">
                                        <input type="radio" name="ticket_type_id" value="{{ $type->id }}" class="radio radio-primary mr-4" {{ $loop->first ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="font-bold text-foreground group-hover:text-primary transition-colors">{{ $type->name }}</div>
                                            <div class="text-sm text-muted-foreground">
                                                @if($type->price > 0)
                                                    ${{ number_format($type->price, 2) }}
                                                @else
                                                    Free
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="text-center py-4 text-muted-foreground italic">
                                        No tickets available.
                                    </div>
                                @endforelse
                            </div>

                            @if($event->ticketTypes->count() > 0)
                                <div class="pt-4">
                                    <label class="text-xs font-bold text-muted-foreground uppercase mb-2 block">Quantity</label>
                                    <select name="quantity" class="select select-bordered w-full">
                                        @foreach(range(1, 10) as $q)
                                            <option value="{{ $q }}">{{ $q }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-full h-12 shadow-lg shadow-primary/20 mt-6">
                                    Get Tickets
                                </button>
                            @endif
                        </form>

                        @guest
                            <p class="text-[10px] text-center text-muted-foreground mt-4">
                                You'll need to <a href="{{ route('login') }}" class="text-primary underline">login</a> or <a href="{{ route('register') }}" class="text-primary underline">register</a> to checkout.
                            </p>
                        @endguest
                    </div>

                    {{-- Date card --}}
                    <div class="bg-muted/30 rounded-2xl p-6 border border-border/40">
                        <div class="flex items-start gap-4">
                            <div class="size-10 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm text-primary">
                                <span class="icon-[lucide--calendar] size-5"></span>
                            </div>
                            <div>
                                <div class="font-bold text-foreground">{{ $event->start_date->format('l, F d') }}</div>
                                <div class="text-sm text-muted-foreground">{{ $event->start_date->format('h:i A') }} - {{ $event->end_date ? $event->end_date->format('h:i A') : 'End TBA' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
