@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.venues.index') }}" class="btn btn-circle btn-ghost">
            <span class="icon-[lucide--arrow-left] size-5"></span>
        </a>
        <h2 class="text-3xl font-bold tracking-tight text-foreground">Add Global Venue</h2>
    </div>

    <form action="{{ route('admin.venues.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
            <div class="grid grid-cols-1 gap-6">
                <div class="flex items-center justify-between p-4 rounded-xl bg-muted/30 border border-border/50">
                    <div>
                        <h4 class="font-bold text-foreground">Global Venue</h4>
                        <p class="text-xs text-muted-foreground">Make this venue available to all organizers.</p>
                    </div>
                    <input type="checkbox" name="is_global" class="switch switch-primary" checked value="1">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-black uppercase text-muted-foreground">Venue Name</label>
                    <input type="text" name="name" required class="input input-bordered w-full" placeholder="e.g. Royal Albert Hall">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-black uppercase text-muted-foreground">Address</label>
                    <input type="text" name="address" class="input input-bordered w-full" placeholder="e.g. Kensington Gore, London SW7 2AP">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-black uppercase text-muted-foreground">Total Capacity</label>
                        <input type="number" name="capacity" min="0" class="input input-bordered w-full" value="0">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-black uppercase text-muted-foreground">Seat Map Image</label>
                        <input type="file" name="seat_map_image" class="file-input file-input-bordered w-full" accept="image/*">
                        <p class="text-xs text-muted-foreground">JPG, PNG, or SVG (Max 2MB)</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-black uppercase text-muted-foreground">Seat Numbers (Optional Mapping)</label>
                    <textarea name="seat_numbers" class="textarea textarea-bordered w-full h-32" placeholder="e.g. A1, A2, A3, B1, B2, B3... (Comma separated)"></textarea>
                    <p class="text-xs text-muted-foreground">If provided, attendees must select one of these seats during checkout.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.venues.index') }}" class="btn btn-ghost px-8">Cancel</a>
            <button type="submit" class="btn btn-primary px-10 shadow-xl shadow-primary/30">Create Venue</button>
        </div>
    </form>
</div>
@endsection
