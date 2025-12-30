@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Global Venues</h2>
            <p class="text-sm text-muted-foreground">Manage venues available to all organizers.</p>
        </div>
        <a href="{{ route('admin.venues.create') }}" class="btn btn-primary gap-2">
            <span class="icon-[lucide--plus] size-5"></span>
            <span>Add Global Venue</span>
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
            <span class="icon-[lucide--check-circle] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($venues as $venue)
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
                    <p class="text-sm text-muted-foreground mb-4">{{ $venue->address ?? 'No address provided' }}</p>
                    
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-2">
                            <span class="badge badge-primary badge-soft font-bold">Cap: {{ $venue->capacity }}</span>
                            @if($venue->seat_numbers)
                                <span class="badge badge-secondary badge-soft font-bold">Seat Mapping</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-sm btn-ghost gap-1" title="Edit">
                                <span class="icon-[lucide--edit-3] size-4"></span>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                <h3 class="text-lg font-medium text-foreground">No global venues found</h3>
                <p class="text-sm text-muted-foreground mb-6">Start by adding your first venue mapping.</p>
                <a href="{{ route('admin.venues.create') }}" class="btn btn-primary btn-outline">Add Global Venue</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
