@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-card text-card-foreground rounded-lg border shadow-sm mb-8">
        <div class="p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6">
            @if($organizer->avatar)
                <div class="size-20 rounded-full overflow-hidden border border-border">
                    <img src="{{ asset('storage/' . $organizer->avatar) }}" alt="{{ $organizer->name }}" class="h-full w-full object-cover">
                </div>
            @else
                <div class="size-20 rounded-full bg-primary/10 flex items-center justify-center text-3xl font-bold text-primary">
                    {{ $organizer->initials() }}
                </div>
            @endif
            
            <div class="flex-1 text-center md:text-left space-y-2">
                <h1 class="text-3xl font-bold">{{ $organizer->name }}</h1>
                <p class="text-muted-foreground">Organizer</p>
                
                <div class="flex items-center justify-center md:justify-start gap-4 text-sm">
                    <div class="flex items-center gap-1">
                        <span class="font-bold">{{ $organizer->followers_count }}</span> followers
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="font-bold">{{ $events->total() }}</span> events
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    @if(Auth::id() !== $organizer->id)
                        @if(Auth::user()->isFollowing($organizer))
                            <form action="{{ route('organizers.unfollow', $organizer) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                    Following
                                </button>
                            </form>
                        @else
                            <form action="{{ route('organizers.follow', $organizer) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                    Follow
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Login to Follow
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold tracking-tight mb-6">Events</h2>

    @if($events->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="rounded-full bg-muted/30 p-4 mb-4">
                <span class="icon-[tabler--calendar-cancel] text-muted-foreground w-8 h-8"></span>
            </div>
            <h3 class="text-lg font-semibold">No upcoming events</h3>
            <p class="text-muted-foreground mt-1">This organizer hasn't published any upcoming events yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($events as $event)
                <a href="{{ route('events.show', $event) }}" class="group block h-full">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm h-full overflow-hidden transition-all hover:shadow-md">
                        <div class="relative h-48 w-full bg-muted">
                            @if($event->cover_image_path)
                                <img src="{{ asset('storage/' . $event->cover_image_path) }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition-transform group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-secondary/10 text-muted-foreground">
                                    <span class="icon-[tabler--photo] w-12 h-12"></span>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                    {{ $event->category->name ?? 'Event' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6 space-y-2">
                            <div class="text-sm text-muted-foreground">
                                {{ $event->start_date->format('D, M j • g:i A') }}
                            </div>
                            <h3 class="font-semibold tracking-tight text-lg line-clamp-2 md:h-14">
                                {{ $event->title }}
                            </h3>
                            <div class="flex items-center text-sm text-muted-foreground">
                                <span class="icon-[tabler--map-pin] w-4 h-4 mr-1"></span>
                                <span class="line-clamp-1">{{ $event->venue ? $event->venue->city : ($event->venue_address ?? 'Online') }}</span>
                            </div>
                            <div class="pt-2 flex items-center justify-between">
                                <div class="font-medium text-primary">
                                    {{ $event->ticketTypes->min('price') > 0 ? '$' . number_format($event->ticketTypes->min('price'), 2) : 'Free' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
