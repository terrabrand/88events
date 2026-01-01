@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-foreground">
                @role('attendee') Upcoming Events @else Event Management @endrole
            </h2>
            @role('organizer|admin')
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <span class="icon-[tabler--plus] size-5"></span>
                Create Event
            </a>
            @endrole
        </div>

        @role('organizer|admin')
            <!-- Management Tabs -->
            <div class="flex border-b border-border mb-4">
                <a href="{{ route('events.index') }}" @class([
                    'px-6 py-3 text-sm font-medium border-b-2 transition-colors',
                    'border-primary text-primary' => request()->routeIs('events.index'),
                    'border-transparent text-muted-foreground hover:text-foreground' => !request()->routeIs('events.index')
                ])>
                    <span class="flex items-center gap-2">
                        <span class="icon-[tabler--list-details] size-4"></span>
                        Event List
                    </span>
                </a>
                <a href="{{ route('organizer.guests.index') }}" @class([
                    'px-6 py-3 text-sm font-medium border-b-2 transition-colors',
                    'border-primary text-primary' => request()->routeIs('organizer.guests.index'),
                    'border-transparent text-muted-foreground hover:text-foreground' => !request()->routeIs('organizer.guests.index')
                ])>
                    <span class="flex items-center gap-2">
                        <span class="icon-[tabler--users-group] size-4"></span>
                        Global Guest Pool
                    </span>
                </a>
            </div>
        @endrole

        @if(session('success'))
            <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
                <span class="icon-[tabler--check] size-5"></span>
                {{ session('success') }}
            </div>
        @endif

        @role('attendee')
            <!-- Marketplace View for Attendees -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow cursor-pointer overflow-hidden group">
                        @if($event->cover_image_path)
                            <figure class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $event->cover_image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            </figure>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold leading-none tracking-tight mb-2">{{ $event->title }}</h3>
                            <p class="text-sm text-muted-foreground flex items-center gap-1 mb-4">
                                <span class="icon-[tabler--calendar] size-4"></span>
                                {{ $event->start_date->format('D, M d h:i A') }}
                            </p>
                            <p class="text-sm text-muted-foreground line-clamp-2 mb-6">{{ $event->description }}</p>
                            <div class="flex justify-end">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">View & Buy</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-muted-foreground">
                        <span class="icon-[tabler--calendar-off] size-12 opacity-30 mb-2"></span>
                        <p>No upcoming events found.</p>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Table View for Organizers -->
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Title</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Type</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                                    <td class="p-4 align-middle">
                                        <div class="font-medium flex items-center gap-2">
                                            {{ $event->title }}
                                            @if($event->is_promoted)
                                                <div class="flex items-center justify-center size-6 rounded-full bg-green-100 text-green-600" title="Promoted">
                                                    <span class="icon-[tabler--currency-dollar] size-4"></span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-xs text-muted-foreground max-w-[200px] truncate">{{ $event->description }}</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div>{{ $event->start_date->format('M d, Y') }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $event->start_date->format('h:i A') }}</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2
                                            {{ $event->location_type === 'online' ? 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80' : 'border-transparent bg-primary text-primary-foreground hover:bg-primary/80' }}">
                                            {{ ucfirst($event->location_type) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2
                                            {{ $event->status === 'published' ? 'border-transparent bg-green-500/15 text-green-700 hover:bg-green-500/25' : 'border-transparent bg-yellow-500/15 text-yellow-700 hover:bg-yellow-500/25' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div class="flex gap-2">
                                            <a href="{{ route('organizer.guests.event', $event) }}" class="btn btn-sm btn-outline btn-primary gap-1.5" aria-label="Guestlist">
                                                <span class="icon-[tabler--users-group] size-4"></span>
                                                <span class="hidden sm:inline">Guests</span>
                                            </a>
                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-ghost btn-square text-muted-foreground hover:text-foreground" aria-label="Edit">
                                                <span class="icon-[tabler--pencil] size-4"></span>
                                            </a>
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-ghost btn-square text-destructive hover:text-destructive/80" aria-label="Delete">
                                                    <span class="icon-[tabler--trash] size-4"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-muted-foreground">
                                        No events found. Start by creating one!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endrole
        
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
@endsection
