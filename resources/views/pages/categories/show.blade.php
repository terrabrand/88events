@extends('layouts.guest')

@section('title', $category->name . ' Events')

@section('content')
<div class="min-h-screen bg-background">
    {{-- Header --}}
    <div class="bg-muted/30 py-16 border-b border-border/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6">
                <div class="size-20 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-sm ring-1 ring-primary/20">
                    <span class="icon-[lucide--{{ $category->icon ?: 'layers' }}] size-10"></span>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-foreground">{{ $category->name }}</h1>
                    <p class="text-muted-foreground mt-2 max-w-2xl">{{ $category->description ?: 'Browse the best events in ' . $category->name . '.' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Events Grid --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-bold text-foreground">{{ $events->total() }} Events found</h2>
            <div class="flex gap-2">
                {{-- Filters could go here --}}
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
            @forelse($events as $event)
                <div class="group h-full flex flex-col hover:shadow-xl transition-all duration-300 rounded-lg overflow-hidden border border-transparent hover:border-border bg-card">
                    {{-- Image Container --}}
                    <a href="{{ route('events.show.public', $event->slug) }}" class="relative aspect-[16/9] overflow-hidden rounded-lg">
                        <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $event->title }}">
                        <div class="absolute top-2 right-2">
                            <button class="size-9 rounded-full bg-white/90 backdrop-blur shadow flex items-center justify-center text-muted-foreground hover:text-primary transition-colors">
                                <span class="icon-[lucide--bookmark] size-5"></span>
                            </button>
                        </div>
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

                        <div class="mt-auto space-y-2">
                            <div class="text-sm font-bold text-[#1E0A3C]">
                                @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                {{ $minPrice > 0 ? 'From $' . number_format($minPrice, 2) : 'Free' }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <span class="icon-[lucide--calendar-x] size-16 text-muted-foreground/30 mb-4"></span>
                    <h3 class="text-xl font-bold text-muted-foreground">No events found in this category</h3>
                    <p class="text-muted-foreground mt-2 pb-6">Check back later for new events!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Browse All Events</a>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
