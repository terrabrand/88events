@extends('layouts.guest')

@section('title', 'Search Results - ' . config('app.name'))

@push('styles')
<style>
    .glass-nav {
        background: rgba(var(--background), 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-background text-foreground">
    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 glass-nav border-b border-border/40 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl h-20 flex items-center justify-between gap-4">
            <div class="flex items-center gap-6 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-1">
                    <span class="icon-[lucide--ticket] size-8 text-[#D1410C]"></span>
                    <span class="text-2xl font-black tracking-tighter text-[#D1410C]">{{ config('app.name') }}</span>
                </a>
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('search') }}" method="GET" class="hidden lg:flex flex-1 max-w-2xl bg-muted/20 border border-border rounded-lg overflow-hidden h-11 items-center shadow-sm">
                <div class="flex items-center gap-2 px-3 border-r border-border h-full flex-1 min-w-0">
                    <span class="icon-[lucide--search] size-4 text-muted-foreground"></span>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search events" class="bg-transparent border-none focus:ring-0 text-sm w-full outline-none">
                </div>
                <div class="flex items-center gap-2 px-3 h-full flex-1 min-w-0 group">
                    <span class="icon-[lucide--map-pin] size-4 text-muted-foreground"></span>
                    <input type="text" name="l" value="{{ $location }}" placeholder="Your Location" class="bg-transparent border-none focus:ring-0 text-sm w-full outline-none">
                    <button type="submit" class="bg-[#D1410C] p-2 rounded-md text-white ml-auto">
                        <span class="icon-[lucide--search] size-4"></span>
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-full btn-sm px-6">Dashboard</a>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-xs font-bold hover:text-primary transition-colors">Log In</a>
                        <a href="{{ route('register') }}" class="text-xs font-bold hover:text-primary transition-colors">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-10">
                <h1 class="text-4xl font-black tracking-tight text-[#1E0A3C] mb-2">
                    @if($query || $location)
                        Search results for 
                        <span class="text-[#3659E3]">
                            "{{ $query ?: ($location ?: 'events') }}"
                            @if($query && $location) in "{{ $location }}" @endif
                        </span>
                    @else
                        All Events
                    @endif
                </h1>
                <p class="text-muted-foreground">{{ $events->total() }} events found</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                @forelse($events as $event)
                    <div class="group h-full flex flex-col hover:shadow-xl transition-all duration-300 rounded-lg overflow-hidden border border-transparent hover:border-border">
                        <div class="relative aspect-[16/9] overflow-hidden rounded-lg">
                            <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $event->title }}">
                        </div>
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
                        <div class="inline-flex items-center justify-center size-20 rounded-full bg-muted mb-6">
                            <span class="icon-[lucide--search-x] size-10 text-muted-foreground"></span>
                        </div>
                        <h3 class="text-xl font-bold text-[#1E0A3C] mb-2">No events found</h3>
                        <p class="text-muted-foreground mb-8">We couldn't find any events matching your search criteria. Try different keywords or location.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-full px-8">Back to Home</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-12 flex justify-center">
                {{ $events->links() }}
            </div>
        </div>
    </main>

    {{-- Footer (Simplified copies for brevity, should use a partial in real app) --}}
    <footer class="bg-[#1E0A3C] text-white pt-20 pb-10 mt-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center text-sm text-white/50">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
</div>
@endsection
