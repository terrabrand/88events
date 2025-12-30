@extends('layouts.guest')

@section('title', 'Welcome to ' . config('app.name'))

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2));
    }
    .glass-nav {
        background: rgba(var(--background), 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .category-scroll::-webkit-scrollbar {
        display: none;
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

            {{-- Two-part Search --}}
            <div class="hidden lg:flex flex-1 max-w-2xl bg-muted/20 border border-border rounded-lg overflow-hidden h-11 items-center shadow-sm">
                <div class="flex items-center gap-2 px-3 border-r border-border h-full flex-1 min-w-0">
                    <span class="icon-[lucide--search] size-4 text-muted-foreground"></span>
                    <input type="text" placeholder="Search events" class="bg-transparent border-none focus:ring-0 text-sm w-full outline-none">
                </div>
                <div class="flex items-center gap-2 px-3 h-full flex-1 min-w-0 group">
                    <span class="icon-[lucide--map-pin] size-4 text-muted-foreground"></span>
                    <input type="text" placeholder="Your Location" class="bg-transparent border-none focus:ring-0 text-sm w-full outline-none">
                    <button class="bg-[#D1410C] p-2 rounded-md text-white ml-auto">
                        <span class="icon-[lucide--search] size-4"></span>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden xl:flex items-center gap-5 mr-2">
                    <a href="#" class="text-xs font-bold hover:text-primary transition-colors">Find Events</a>
                    <a href="{{ route('events.create') }}" class="text-xs font-bold hover:text-primary transition-colors">Create Events</a>
                    <div class="dropdown dropdown-hover dropdown-end">
                        <button tabindex="0" class="text-xs font-bold flex items-center gap-1 hover:text-primary transition-colors">
                            Help Center <span class="icon-[lucide--chevron-down] size-3"></span>
                        </button>
                        
                    </div>
                    <a href="#" class="text-xs font-bold hover:text-primary transition-colors">Find my tickets</a>
                </div>

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

    {{-- Hero Carousel --}}
    <section class="py-2">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative h-[400px] md:h-[550px] w-full rounded-2xl overflow-hidden shadow-xl" 
                 x-data="{ active: 0, count: {{ $featuredItems->count() ?: 1 }}, interval: null }" 
                 x-init="interval = setInterval(() => active = (active + 1) % count, 5000)">
                
                @forelse($featuredItems as $index => $item)
                    @php
                        $image = $item->type === 'event' 
                            ? ($item->event->cover_image_path ? asset('storage/' . $item->event->cover_image_path) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070')
                            : ($item->image_path ? asset('storage/' . $item->image_path) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070');
                        
                        $title = $item->type === 'event' ? $item->event->title : $item->title;
                        $desc = $item->description ?: ($item->type === 'event' ? Str::limit($item->event->description, 100) : '');
                        $link = $item->type === 'event' ? route('events.show.public', $item->event->slug) : $item->link_url;
                        $btnText = $item->button_text ?: ($item->type === 'event' ? 'Buy Tickets Now' : 'Learn More');
                    @endphp

                    <div class="absolute inset-0 transition-all duration-1000 ease-in-out transform" 
                         x-show="active === {{ $index }}"
                         x-transition:enter="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">
                        <img src="{{ $image }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $title }}">
                        <div class="absolute inset-0 bg-black/40"></div>
                        <div class="absolute inset-x-0 bottom-0 top-0 flex flex-col justify-center p-8 md:p-16 max-w-4xl text-white">
                            @if($item->type === 'custom')
                                <h2 class="text-xs font-black uppercase tracking-[0.3em] mb-4 text-primary">Featured Spotlight</h2>
                            @endif
                            <h1 class="text-4xl md:text-[5rem] font-bold tracking-tight leading-[0.95] mb-8 uppercase italic font-serif">
                                {{ $title }}
                            </h1>
                            <p class="text-lg md:text-xl text-white/90 mb-10 max-w-2xl font-light italic leading-relaxed">
                                {{ $desc }}
                            </p>
                            <div>
                                <a href="{{ $link }}" class="btn rounded-full px-10 py-5 h-auto bg-[#FBF2C4] text-black border-none font-bold text-lg hover:bg-white transition-all shadow-lg hover:scale-105">
                                    {{ $btnText }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Default Slide --}}
                    <div class="absolute inset-0">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070" 
                            class="absolute inset-0 w-full h-full object-cover" alt="Join the Vibe">
                        <div class="absolute inset-0 bg-black/30"></div>
                        <div class="absolute inset-x-0 bottom-0 top-0 flex flex-col justify-center p-8 md:p-16 max-w-4xl text-white">
                            <h1 class="text-5xl md:text-[5.5rem] font-bold tracking-tight leading-[0.9] mb-8 uppercase italic font-serif">
                                THIS HOLIDAY SEASON <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 via-orange-400 to-yellow-400">MAKE IT MEMORABLE</span>
                            </h1>
                            <div>
                                <a href="#" class="btn rounded-full px-10 py-5 h-auto bg-[#FBF2C4] text-black border-none font-bold text-lg hover:bg-white transition-all shadow-lg">
                                    Explore Events
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
                
                {{-- Carousel Controls --}}
                <div class="absolute top-1/2 -translate-y-1/2 left-6 z-10">
                    <button @click="active = (active - 1 + count) % count; clearInterval(interval)" class="size-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all">
                        <span class="icon-[lucide--chevron-left] size-6"></span>
                    </button>
                </div>
                <div class="absolute top-1/2 -translate-y-1/2 right-6 z-10">
                    <button @click="active = (active + 1) % count; clearInterval(interval)" class="size-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all">
                        <span class="icon-[lucide--chevron-right] size-6"></span>
                    </button>
                </div>

                {{-- Indicators --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(i, index) in Array.from({length: count})">
                        <div class="size-1.5 rounded-full transition-all duration-300" :class="active === index ? 'bg-white w-8' : 'bg-white/30'"></div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="py-12 border-b border-border/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex gap-8 overflow-x-auto category-scroll pb-4 scroll-smooth">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="flex flex-col items-center gap-3 shrink-0 group relative">
                        <div class="size-20 rounded-full flex items-center justify-center transition-all duration-300 ring-1 ring-border group-hover:ring-primary group-hover:scale-110 shadow-sm bg-primary/5 text-primary">
                            <span class="icon-[lucide--{{ $category->icon ?: 'layers' }}] size-8 group-hover:animate-pulse"></span>
                        </div>
                        <span class="text-xs font-bold tracking-tight text-muted-foreground group-hover:text-foreground transition-colors text-center max-w-[100px]">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Browsing Events Section --}}
    <section class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-8 mb-10">
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-black tracking-tight text-[#1E0A3C]">Browsing events in</h2>
                    <div class="dropdown">
                        <button tabindex="0" class="text-3xl font-black text-[#3659E3] flex items-center gap-2 hover:opacity-80 transition-opacity">
                            <span class="icon-[lucide--chevron-down] size-8"></span>
                            Choose a location
                        </button>
                        <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-card border rounded-box w-52 mt-2 z-50">
                            <li><a href="#">Online</a></li>
                            <li><a href="#">New York</a></li>
                            <li><a href="#">London</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="flex gap-8 border-b border-border pb-1">
                    <button class="text-sm font-bold pb-3 border-b-2 border-primary text-primary">All</button>
                    <button class="text-sm font-bold pb-3 text-muted-foreground hover:text-foreground transition-colors">For you</button>
                    <button class="text-sm font-bold pb-3 text-muted-foreground hover:text-foreground transition-colors">Today</button>
                    <button class="text-sm font-bold pb-3 text-muted-foreground hover:text-foreground transition-colors">This weekend</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                @forelse($latestEvents as $event)
                    <div class="group h-full flex flex-col hover:shadow-xl transition-all duration-300 rounded-lg overflow-hidden border border-transparent hover:border-border">
                        {{-- Image Container --}}
                        <div class="relative aspect-[16/9] overflow-hidden rounded-lg">
                            <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $event->title }}">
                            <div class="absolute top-2 right-2">
                                <button class="size-9 rounded-full bg-white/90 backdrop-blur shadow flex items-center justify-center text-muted-foreground hover:text-primary transition-colors">
                                    <span class="icon-[lucide--bookmark] size-5"></span>
                                </button>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="pt-4 flex flex-col flex-1 px-1">
                            <h3 class="font-bold text-lg text-[#1E0A3C] mb-1 line-clamp-2 leading-snug group-hover:text-[#3659E3] transition-colors">
                                <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
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
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] text-muted-foreground">Promoted</span>
                                    <span class="icon-[lucide--info] size-3 text-muted-foreground"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @foreach(range(1, 4) as $i)
                        <div class="group h-full animate-pulse flex flex-col">
                            <div class="aspect-[16/9] bg-muted rounded-lg"></div>
                            <div class="pt-4 flex-1 space-y-3">
                                <div class="h-6 bg-muted rounded w-full"></div>
                                <div class="h-4 bg-muted rounded w-1/2"></div>
                                <div class="h-4 bg-muted rounded w-3/4"></div>
                                <div class="h-4 bg-muted rounded w-1/4 mt-4"></div>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
            
            <div class="mt-12 flex justify-center">
                <a href="#" class="btn btn-outline btn-lg rounded-full px-12 border-border text-foreground hover:bg-muted font-bold transition-all hover:scale-105 active:scale-95">
                    View More Events
                </a>
            </div>
        </div>
    </section>

    {{-- Top Destinations --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-[#1E0A3C]">Top destinations in United States</h2>
                <div class="flex gap-2">
                    <button class="size-10 rounded-full border border-border flex items-center justify-center hover:bg-muted transition-colors">
                        <span class="icon-[lucide--chevron-left] size-6"></span>
                    </button>
                    <button class="size-10 rounded-full border border-border flex items-center justify-center hover:bg-muted transition-colors">
                        <span class="icon-[lucide--chevron-right] size-6"></span>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $cities = [
                        ['name' => 'New York', 'img' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?q=80&w=2070'],
                        ['name' => 'Los Angeles', 'img' => 'https://images.unsplash.com/photo-1444723121867-7a241cacace9?q=80&w=2070'],
                        ['name' => 'Chicago', 'img' => 'https://images.unsplash.com/photo-1494522855154-9297ac14b55f?q=80&w=2070'],
                        ['name' => 'Washington', 'img' => 'https://images.unsplash.com/photo-1501466044931-62695aada8e9?q=80&w=2070'],
                    ];
                @endphp
                @foreach($cities as $city)
                    <a href="#" class="relative aspect-[4/3] rounded-3xl overflow-hidden group shadow-lg">
                        <img src="{{ $city['img'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="{{ $city['name'] }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute inset-0 p-6 flex flex-col justify-end">
                            <h3 class="text-2xl font-bold text-white">{{ $city['name'] }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Popular Cities Section --}}
    <section class="py-20 bg-muted/20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-[#1E0A3C] mb-12">Popular cities</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @php
                    $popularCities = [
                        'Austin', 'Abilene', 'Denver', 'Seattle', 'Phoenix', 'Albuquerque',
                        'Detroit', 'Anaheim', 'Raleigh', 'Baltimore', 'Nashville', 'Wichita',
                        'Indianapolis', 'San Antonio'
                    ];
                @endphp
                @foreach($popularCities as $city)
                    <a href="#" class="flex items-center justify-between p-4 bg-white rounded-lg border border-transparent hover:border-border hover:shadow-sm transition-all group">
                        <span class="text-sm font-medium text-muted-foreground group-hover:text-[#1E0A3C]">Things to do in {{ $city }}</span>
                        <span class="icon-[lucide--arrow-up-right] size-4 text-muted-foreground/50 group-hover:text-[#1E0A3C]"></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-[#1E0A3C] text-white pt-20 pb-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 mb-20 text-xs">
                <div>
                    <h4 class="font-bold mb-6">Use Eventbrite</h4>
                    <ul class="space-y-4 text-white/70">
                        <li><a href="#" class="hover:text-white transition-colors">Create Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Event Marketing Platform</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Eventbrite Mobile Ticket App</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Eventbrite Check-In App</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Eventbrite App Marketplace</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Event Registration Software</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Community Guidelines</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sitemap</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-6">Plan Events</h4>
                    <ul class="space-y-4 text-white/70">
                        <li><a href="#" class="hover:text-white transition-colors">Sell Tickets Online</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Performance Arts Ticketing Software</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sell Concert Tickets Online</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Event Payment System</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Solutions for Professional Services</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Event Management Software</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Halloween Party Planning</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Virtual Events Platform</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">QR Codes for Event Check-In</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Post your event online</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-6">Find Events</h4>
                    <ul class="space-y-4 text-white/70">
                        <li><a href="#" class="hover:text-white transition-colors">New Orleans Food & Drink Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">San Francisco Holiday Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tulum Music Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Denver Hobby Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Atlanta Pop Music Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">New York Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Chicago Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Events in Dallas Today</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Los Angeles Events</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Washington Events</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-6">Connect With Us</h4>
                    <ul class="space-y-4 text-white/70">
                        <li><a href="#" class="hover:text-white transition-colors">Contact Support</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Sales</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">X</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Facebook</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">LinkedIn</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Instagram</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">TikTok</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-10 border-t border-white/10 text-[10px] text-white/50">
                <div class="flex items-center gap-6">
                    <div>&copy; {{ date('Y') }} Eventbrite</div>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-white">How It Works</a>
                        <a href="#" class="hover:text-white">Pricing</a>
                        <a href="#" class="hover:text-white">Contact Support</a>
                        <a href="#" class="hover:text-white">About</a>
                        <a href="#" class="hover:text-white">Blog</a>
                        <a href="#" class="hover:text-white">Help</a>
                        <a href="#" class="hover:text-white">Careers</a>
                        <a href="#" class="hover:text-white">Press</a>
                        <a href="#" class="hover:text-white">Impact</a>
                        <a href="#" class="hover:text-white">Investors</a>
                        <a href="#" class="hover:text-white">Security</a>
                        <a href="#" class="hover:text-white">Developers</a>
                        <a href="#" class="hover:text-white">Status</a>
                        <a href="#" class="hover:text-white">Terms</a>
                        <a href="#" class="hover:text-white">Privacy</a>
                        <a href="#" class="hover:text-white">Accessibility</a>
                        <a href="#" class="hover:text-white">Cookies</a>
                    </div>
                </div>
                <div class="dropdown dropdown-top dropdown-end">
                    <button tabindex="0" class="hover:text-white flex items-center gap-1 uppercase">
                        United States <span class="icon-[lucide--chevron-up] size-3"></span>
                    </button>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-[#1E0A3C] border border-white/10 rounded-box w-52 mt-2 z-50">
                        <li><a href="#">United Kingdom</a></li>
                        <li><a href="#">France</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
