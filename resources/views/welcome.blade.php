@extends('layouts.public')

@section('title', 'Welcome to ' . config('app.name'))

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2));
    }
    .category-scroll::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('public-content')
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
                        $description = $item->type === 'event' 
                            ? \Illuminate\Support\Str::limit($item->event->description, 150) 
                            : $item->description;
                        
                        $link = $item->type === 'event' ? route('events.show', $item->event) : $item->link_url;
                    @endphp

                    <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                         x-show="active === {{ $index }}"
                         x-transition:enter="opacity-0"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="opacity-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <img src="{{ $image }}" class="w-full h-full object-cover" alt="{{ $title }}">
                        <div class="absolute inset-0 hero-gradient flex items-center">
                            <div class="px-8 md:px-16 md:w-2/3">
                                <span class="inline-block px-3 py-1 mb-4 text-xs font-bold tracking-wider text-primary-foreground uppercase bg-primary rounded-full">
                                    Featured
                                </span>
                                <h1 class="text-4xl md:text-6xl font-black text-white mb-4 leading-tight">
                                    {{ $title }}
                                </h1>
                                <p class="text-lg md:text-xl text-white/90 mb-8 line-clamp-2 max-w-xl">
                                    {{ $description }}
                                </p>
                                @if($link)
                                    <a href="{{ $link }}" class="btn btn-primary btn-lg border-none hover:scale-105 transition-transform shadow-lg shadow-primary/20 text-primary-foreground min-w-[160px]">
                                        Explore Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Default Slide --}}
                    <div class="absolute inset-0">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070" class="w-full h-full object-cover" alt="Featured Event">
                        <div class="absolute inset-0 hero-gradient flex items-center">
                            <div class="px-8 md:px-16 md:w-2/3">
                                <span class="inline-block px-3 py-1 mb-4 text-xs font-bold tracking-wider text-primary-foreground uppercase bg-primary rounded-full">
                                    Discover
                                </span>
                                <h1 class="text-4xl md:text-6xl font-black text-white mb-4 leading-tight">
                                    Experience the Best Events
                                </h1>
                                <p class="text-lg md:text-xl text-white/90 mb-8 max-w-xl">
                                    Find and book tickets for concerts, workshops, conferences, and more in your city.
                                </p>
                                <a href="{{ route('search') }}" class="btn btn-primary btn-lg border-none hover:scale-105 transition-transform shadow-lg shadow-primary/20 text-primary-foreground min-w-[160px]">
                                    Browse Events
                                </a>
                            </div>
                        </div>
                    </div>

                @endforelse

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
                    <h2 class="text-3xl font-black tracking-tight text-foreground">Browsing events in</h2>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="text-3xl font-black text-primary flex items-center gap-2 hover:opacity-80 transition-opacity">
                            <span class="icon-[lucide--chevron-down] size-8 transition-transform duration-200" :class="{ 'rotate-180': open }"></span>
                            Choose a location
                        </button>
                        <ul x-show="open" x-transition class="absolute top-full left-0 mt-2 p-2 shadow-lg bg-card text-card-foreground border border-border rounded-xl w-52 z-50" style="display: none;">
                            <li><a href="#" class="block px-4 py-2 rounded-lg hover:bg-muted text-muted-foreground hover:text-foreground">Online</a></li>
                            <li><a href="#" class="block px-4 py-2 rounded-lg hover:bg-muted text-muted-foreground hover:text-foreground">New York</a></li>
                            <li><a href="#" class="block px-4 py-2 rounded-lg hover:bg-muted text-muted-foreground hover:text-foreground">London</a></li>
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
                            <h3 class="font-bold text-lg text-foreground mb-1 line-clamp-2 leading-snug group-hover:text-primary transition-colors">
                                <a href="{{ route('events.show.public', $event->slug) }}">{{ $event->title }}</a>
                            </h3>
                            <div class="text-sm font-bold text-primary mb-1 uppercase tracking-tight">{{ $event->start_date->format('D, M d • h:i A T') }}</div>
                            
                            <div class="text-sm text-muted-foreground mb-4">
                                {{ $event->venue?->name ?? $event->venue_address ?? 'Location TBA' }}
                            </div>

                            <div class="mt-auto space-y-2">
                                <div class="text-sm font-bold text-foreground">
                                    @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                    {{ $minPrice > 0 ? 'From $' . number_format($minPrice, 2) : 'Free' }}
                                </div>
                                @if($event->is_promoted)
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] text-muted-foreground font-semibold">Promoted</span>
                                        <span class="icon-[lucide--info] size-3 text-muted-foreground"></span>
                                    </div>
                                @endif
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
    <section class="py-20 bg-background">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Top destinations in United States</h2>
                <div class="flex gap-2">
                    <button class="size-10 rounded-full border border-border flex items-center justify-center hover:bg-muted text-foreground transition-colors">
                        <span class="icon-[lucide--chevron-left] size-6"></span>
                    </button>
                    <button class="size-10 rounded-full border border-border flex items-center justify-center hover:bg-muted text-foreground transition-colors">
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
            <h2 class="text-2xl font-bold tracking-tight text-foreground mb-12">Popular cities</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @php
                    $popularCities = [
                        'Austin', 'Abilene', 'Denver', 'Seattle', 'Phoenix', 'Albuquerque',
                        'Detroit', 'Anaheim', 'Raleigh', 'Baltimore', 'Nashville', 'Wichita',
                        'Indianapolis', 'San Antonio'
                    ];
                @endphp
                @foreach($popularCities as $city)
                    <a href="#" class="flex items-center justify-between p-4 bg-card text-card-foreground rounded-lg border border-transparent hover:border-border hover:shadow-sm transition-all group">
                        <span class="text-sm font-medium text-muted-foreground group-hover:text-primary">Things to do in {{ $city }}</span>
                        <span class="icon-[lucide--arrow-up-right] size-4 text-muted-foreground/50 group-hover:text-primary"></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

</div>
@endsection
