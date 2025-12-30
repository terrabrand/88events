@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('events.index') }}" class="btn btn-ghost btn-circle btn-sm">
                <span class="icon-[tabler--arrow-left] size-5"></span>
            </a>
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Event Guestlist</h2>
                <p class="text-muted-foreground mt-1">Managing guests for: <span class="text-primary font-semibold underline underline-offset-4 decoration-primary/30 uppercase tracking-tight">{{ $event->title }}</span></p>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="btn btn-outline gap-2 h-11" onclick="window.import_guests_modal.showModal()">
                <span class="icon-[tabler--database-import] size-5"></span>
                Import Pool
            </button>
            <button class="btn btn-primary gap-2 h-11" onclick="window.add_event_guest_modal.showModal()">
                <span class="icon-[tabler--user-plus] size-5"></span>
                Quick Add
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Guestlist Table -->
    <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/30">
                        <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Name</th>
                        <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Contact</th>
                        <th class="h-12 px-6 text-center align-middle font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                        <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Joined</th>
                        <th class="h-12 px-6 text-right align-middle font-semibold text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="px-6 py-4 align-middle font-bold text-foreground">{{ $guest->name }}</td>
                            <td class="px-6 py-4 align-middle italic text-muted-foreground">
                                {{ $guest->email ?: $guest->phone ?: 'No record' }}
                            </td>
                            <td class="px-6 py-4 align-middle text-center">
                                @if($guest->pivot->status === 'checked-in')
                                    <span class="badge badge-soft badge-success animate-in fade-in zoom-in duration-300">
                                        <span class="icon-[tabler--circle-check] mr-1.5 size-3.5"></span>
                                        Checked In
                                    </span>
                                @else
                                    <span class="badge badge-soft badge-primary">Invited</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-middle text-muted-foreground text-xs">{{ $guest->pivot->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 align-middle text-right">
                                <div class="flex justify-end gap-1">
                                    <form action="{{ route('organizer.guests.event.status', [$event, $guest]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" @class([
                                            'btn btn-sm btn-square transition-all duration-200',
                                            'btn-success' => $guest->pivot->status === 'checked-in',
                                            'btn-ghost hover:bg-success/20' => $guest->pivot->status !== 'checked-in'
                                        ]) title="{{ $guest->pivot->status === 'checked-in' ? 'Cancel Check-in' : 'Check-in Guest' }}">
                                            <span @class([
                                                'size-4',
                                                'icon-[tabler--user-check]' => $guest->pivot->status !== 'checked-in',
                                                'icon-[tabler--user-x]' => $guest->pivot->status === 'checked-in'
                                            ])></span>
                                        </button>
                                    </form>
                                    <form action="{{ route('organizer.guests.event.remove', [$event, $guest]) }}" method="POST" onsubmit="return confirm('Remove guest from this event?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-ghost btn-square text-destructive hover:bg-destructive/10" title="Remove from event">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-muted-foreground">
                                <div class="max-w-xs mx-auto space-y-4 opacity-60">
                                    <span class="icon-[tabler--users-group] size-16 mb-2"></span>
                                    <p class="text-lg font-medium">Guestlist is empty</p>
                                    <p class="text-sm italic">Add guests or import from your global pool.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Quick Guest Modal -->
    <dialog id="add_event_guest_modal" class="modal">
        <div class="modal-box max-w-md border border-border shadow-2xl">
            <div class="mb-6">
                <h3 class="font-bold text-2xl text-foreground">Quick Add Guest</h3>
                <p class="text-sm text-muted-foreground mt-1">Guest will be added to this event and your pool.</p>
            </div>
            
            <form action="{{ route('organizer.guests.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="space-y-2">
                    <label class="text-sm font-semibold px-1">Full Name</label>
                    <input type="text" name="name" class="input input-bordered w-full h-11 shadow-sm focus:ring-2 focus:ring-primary/20" placeholder="e.g. John Doe" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Email</label>
                        <input type="email" name="email" class="input input-bordered w-full h-11 shadow-sm focus:ring-2 focus:ring-primary/20" placeholder="john@example.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold px-1">Phone</label>
                        <input type="text" name="phone" class="input input-bordered w-full h-11 shadow-sm focus:ring-2 focus:ring-primary/20" placeholder="+123...">
                    </div>
                </div>
                <div class="modal-action gap-3">
                    <button type="button" onclick="window.add_event_guest_modal.close()" class="btn btn-ghost flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-primary/20">Invite Guest</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <!-- Import Modal -->
    <dialog id="import_guests_modal" class="modal">
        <div class="modal-box max-w-2xl border border-border shadow-2xl">
            <div class="mb-6">
                <h3 class="font-bold text-2xl text-foreground text-center">Import from Pool</h3>
                <p class="text-sm text-muted-foreground text-center mt-1">Select previous guests to join this event.</p>
            </div>
            
            <form action="{{ route('organizer.guests.import', $event) }}" method="POST" class="space-y-6">
                @csrf
                <div class="max-h-[350px] overflow-y-auto border border-border rounded-xl shadow-inner bg-muted/5">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-background border-b border-border shadow-sm">
                            <tr>
                                <th class="p-3 text-left w-12 text-center">#</th>
                                <th class="p-3 text-left font-semibold text-muted-foreground uppercase text-xs">Name</th>
                                <th class="p-3 text-left font-semibold text-muted-foreground uppercase text-xs">Contact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @forelse($previousGuests as $pgest)
                                <tr class="hover:bg-primary/5 transition-colors cursor-pointer group" onclick="this.querySelector('input').click()">
                                    <td class="p-4 text-center">
                                        <input type="checkbox" name="guest_ids[]" value="{{ $pgest->id }}" class="checkbox checkbox-primary checkbox-sm shadow-sm" onclick="event.stopPropagation()">
                                    </td>
                                    <td class="p-4 font-bold text-foreground group-hover:text-primary transition-colors">{{ $pgest->name }}</td>
                                    <td class="p-4 italic text-muted-foreground font-mono text-xs">{{ $pgest->email ?: $pgest->phone ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-12 text-center text-muted-foreground italic">No other guests in your pool to import.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="modal-action gap-3">
                    <button type="button" onclick="window.import_guests_modal.close()" class="btn btn-ghost flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1 shadow-lg shadow-primary/20" @if($previousGuests->isEmpty()) disabled @endif>
                        Import Selected
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>
</div>
@endsection
