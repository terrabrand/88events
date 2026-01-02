@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Event Management</h2>
            <p class="text-muted-foreground mt-1">Manage your events and guest pools from one place.</p>
        </div>
        <button class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all" onclick="window.add_guest_modal.showModal()">
            <span class="icon-[tabler--user-plus] size-5"></span>
            Add Global Guest
        </button>
    </div>

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

    @if(session('success'))
        <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/30">
                        <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Name</th>
                        <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Email</th>
                        <th class="h-12 px-6 text-left align-middle font-semibold text-muted-foreground uppercase tracking-wider">Phone</th>
                        <th class="h-12 px-6 text-center align-middle font-semibold text-muted-foreground uppercase tracking-wider">History</th>
                        <th class="h-12 px-6 text-right align-middle font-semibold text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="px-6 py-4 align-middle">
                                <div class="font-bold text-foreground">{{ $guest->name }}</div>
                            </td>
                            <td class="px-6 py-4 align-middle font-mono text-xs text-muted-foreground">
                                {{ $guest->email ?: '—' }}
                            </td>
                            <td class="px-6 py-4 align-middle text-muted-foreground">
                                {{ $guest->phone ?: '—' }}
                            </td>
                            <td class="px-6 py-4 align-middle text-center">
                                <span class="badge badge-soft badge-primary px-3">{{ $guest->events_count }} Events</span>
                            </td>
                            <td class="px-6 py-4 align-middle text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="openInviteModal({{ $guest->id }}, '{{ addslashes($guest->name) }}')" class="btn btn-sm btn-ghost btn-square text-primary hover:bg-primary/10" title="Invite to Event">
                                        <span class="icon-[tabler--calendar-plus] size-4"></span>
                                    </button>
                                    <form action="{{ route('organizer.guests.destroy', $guest) }}" method="POST" onsubmit="return confirm('Delete this guest from global list?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-ghost btn-square text-destructive hover:bg-destructive/10" title="Delete Guest">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-muted-foreground italic">
                                Your global guest list is empty. Guests are usually added from event guestlists.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($guests->hasPages())
            <div class="p-4 border-t border-border bg-muted/5">
                {{ $guests->links() }}
            </div>
        @endif
    </div>

    <!-- Invite to Event Modal -->
    <dialog id="invite_to_event_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
        <form method="dialog" class="fixed inset-0 outline-none w-full h-full cursor-pointer" tabindex="-1"></form>
        <div class="modal-box w-full max-w-md bg-card text-card-foreground border border-border shadow-2xl rounded-2xl p-6 relative z-10 mx-4" onclick="event.stopPropagation()">
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h3 class="font-black text-2xl text-foreground tracking-tight">Invite to Event</h3>
                    <p class="text-sm text-muted-foreground mt-1">Select events to add <span id="invite_guest_name" class="font-bold text-primary"></span> to.</p>
                </div>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost text-muted-foreground hover:text-foreground hover:bg-muted transition-colors -mr-2 -mt-2">
                        <span class="icon-[tabler--x] size-5"></span>
                    </button>
                </form>
            </div>
            
            <form id="invite_form" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($events as $event)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-border hover:bg-muted/30 hover:border-primary/30 cursor-pointer transition-all">
                            <input type="checkbox" name="event_ids[]" value="{{ $event->id }}" class="checkbox checkbox-primary rounded-md shadow-sm">
                            <div class="flex-1">
                                <div class="font-bold text-foreground">{{ $event->title }}</div>
                                <div class="text-xs text-muted-foreground">{{ $event->start_date->format('M d, Y') }}</div>
                            </div>
                        </label>
                    @empty
                        <div class="p-8 text-center border-2 border-dashed border-border rounded-xl bg-muted/20">
                            <span class="icon-[tabler--calendar-cancel] size-8 text-muted-foreground/50 mb-2"></span>
                            <p class="text-muted-foreground italic text-sm">No upcoming published events found.</p>
                        </div>
                    @endforelse
                </div>

                <div class="modal-action gap-3 mt-8">
                    <button type="button" onclick="window.invite_to_event_modal.close()" class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary text-primary-foreground flex-1 shadow-lg shadow-primary/20 font-bold" @if($events->isEmpty()) disabled @endif>
                        Send Invitations
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openInviteModal(guestId, guestName) {
            document.getElementById('invite_guest_name').innerText = guestName;
            document.getElementById('invite_form').action = `/organizer/guests/${guestId}/invite`;
            window.invite_to_event_modal.showModal();
        }
    </script>

    <!-- Add Guest Modal -->
    <dialog id="add_guest_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
        <form method="dialog" class="fixed inset-0 outline-none w-full h-full cursor-pointer" tabindex="-1"></form>
        <div class="modal-box w-full max-w-md bg-card text-card-foreground border border-border shadow-2xl rounded-2xl p-6 relative z-10 mx-4" onclick="event.stopPropagation()">
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h3 class="font-black text-2xl text-foreground tracking-tight">New Global Guest</h3>
                    <p class="text-sm text-muted-foreground mt-1">Add a guest to your permanent contact pool.</p>
                </div>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost text-muted-foreground hover:text-foreground hover:bg-muted transition-colors -mr-2 -mt-2">
                        <span class="icon-[tabler--x] size-5"></span>
                    </button>
                </form>
            </div>
            
            <form action="{{ route('organizer.guests.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-bold text-foreground px-1">Full Name</label>
                    <input type="text" name="name" class="input input-bordered w-full h-11 bg-input text-foreground focus:ring-2 focus:ring-primary/20 focus:border-primary border-border" placeholder="e.g. John Doe" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-foreground px-1">Email Address</label>
                        <input type="email" name="email" class="input input-bordered w-full h-11 bg-input text-foreground focus:ring-2 focus:ring-primary/20 focus:border-primary border-border" placeholder="john@example.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-foreground px-1">Phone Number</label>
                        <input type="text" name="phone" class="input input-bordered w-full h-11 bg-input text-foreground focus:ring-2 focus:ring-primary/20 focus:border-primary border-border" placeholder="+123...">
                    </div>
                </div>
                
                <div class="modal-action gap-3 mt-8">
                    <button type="button" onclick="window.add_guest_modal.close()" class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">
                        Add to Pool
                    </button>
                </div>
            </form>
        </div>
    </dialog>
</div>
@endsection
