@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6" x-data="{ activeTab: 'my-venues' }">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Venues</h2>
            <p class="text-sm text-muted-foreground">Manage your private venues or browse global ones.</p>
        </div>
        <a href="{{ route('organizer.venues.create') }}" class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">
            <span class="icon-[lucide--plus] size-5"></span>
            Add Private Venue
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
            <span class="icon-[lucide--check-circle] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <div class="tabs tabs-box bg-muted/30 p-1 rounded-xl w-fit">
        <button class="tab" :class="{ 'tab-active': activeTab === 'my-venues' }" @click="activeTab = 'my-venues'">
            My Venues
        </button>
        <button class="tab" :class="{ 'tab-active': activeTab === 'global-library' }" @click="activeTab = 'global-library'">
            Global Library
        </button>
    </div>

    {{-- My Venues Tab --}}
    <div x-show="activeTab === 'my-venues'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($myVenues as $venue)
            <div class="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-primary/20 hover:shadow-md">
                @if($venue->seat_map_image)
                    <div class="aspect-video w-full overflow-hidden border-b border-border bg-muted">
                        <img src="{{ asset('storage/' . $venue->seat_map_image) }}" alt="{{ $venue->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </div>
                @else
                    <div class="aspect-video w-full flex items-center justify-center border-b border-border bg-muted">
                        <span class="icon-[lucide--map-pin] size-12 text-muted-foreground/30"></span>
                    </div>
                @endif
                <div class="p-5">
                    <h3 class="text-lg font-bold text-foreground mb-1">{{ $venue->name }}</h3>
                    <p class="text-sm text-muted-foreground mb-4">{{ $venue->address ?? 'No address' }}</p>
                    
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-2">
                            <span class="badge badge-primary badge-soft font-bold">Cap: {{ $venue->capacity }}</span>
                            @if($venue->seat_numbers)
                                <span class="badge badge-secondary badge-soft font-bold">Seats</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('organizer.venues.edit', $venue) }}" class="btn btn-sm btn-ghost gap-1" title="Edit">
                                <span class="icon-[lucide--edit-3] size-4"></span>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('organizer.venues.destroy', $venue) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost text-error gap-1" title="Delete">
                                    <span class="icon-[lucide--trash-2] size-4"></span>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-card rounded-2xl border border-dashed border-border">
                <span class="icon-[lucide--landmark] size-12 text-muted-foreground/30 mx-auto mb-4"></span>
                <h3 class="text-lg font-medium text-foreground">No personal venues</h3>
                <p class="text-sm text-muted-foreground mb-6">Create a new venue or pull one from the library.</p>
                <a href="{{ route('organizer.venues.create') }}" class="btn btn-primary btn-outline btn-sm">Create Private Venue</a>
            </div>
        @endforelse
    </div>

    {{-- Global Library Tab --}}
    <div x-show="activeTab === 'global-library'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($globalVenues as $venue)
            <div class="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-primary/20 hover:shadow-md">
                <div class="absolute top-3 right-3 z-10">
                    <span class="badge badge-primary font-bold uppercase text-[10px]">Library</span>
                </div>
                
                @if($venue->seat_map_image)
                    <div class="aspect-video w-full overflow-hidden border-b border-border bg-muted">
                        <img src="{{ asset('storage/' . $venue->seat_map_image) }}" alt="{{ $venue->name }}" class="h-full w-full object-cover opacity-60">
                    </div>
                @else
                    <div class="aspect-video w-full flex items-center justify-center border-b border-border bg-muted">
                        <span class="icon-[lucide--map-pin] size-12 text-muted-foreground/10"></span>
                    </div>
                @endif
                <div class="p-5">
                    <h3 class="text-lg font-bold text-foreground mb-1">{{ $venue->name }}</h3>
                    <p class="text-sm text-muted-foreground mb-4">{{ $venue->address ?? 'Global Venue' }}</p>
                    
                    <div class="flex justify-between items-center">
                         <div class="flex items-center gap-2">
                            <span class="badge badge-primary badge-soft font-bold italic">Global</span>
                            <span class="badge badge-muted badge-soft font-bold">Cap: {{ $venue->capacity }}</span>
                        </div>
                        <form action="{{ route('organizer.venues.pull', $venue) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm rounded-lg gap-1">
                                <span class="icon-[lucide--download] size-4"></span>
                                <span>Use This (Clone)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-card rounded-2xl border border-dashed border-border">
                <span class="icon-[lucide--library] size-12 text-muted-foreground/30 mx-auto mb-4"></span>
                <p class="text-sm text-muted-foreground">The global venue library is currently empty.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
