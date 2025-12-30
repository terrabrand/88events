@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.venues.index') }}" class="btn btn-circle btn-ghost">
            <span class="icon-[lucide--arrow-left] size-5"></span>
        </a>
        <h2 class="text-3xl font-bold tracking-tight text-foreground">Edit Global Venue</h2>
    </div>

    <form action="{{ route('admin.venues.update', $venue) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
            <div class="grid grid-cols-1 gap-6">
                <div class="flex items-center justify-between p-4 rounded-xl bg-muted/30 border border-border/50">
                    <div>
                        <h4 class="font-bold text-foreground">Global Venue</h4>
                        <p class="text-xs text-muted-foreground">Make this venue available to all organizers.</p>
                    </div>
                    <input type="checkbox" name="is_global" class="switch switch-primary" {{ $venue->is_global ? 'checked' : '' }} value="1">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-black uppercase text-muted-foreground">Venue Name</label>
                    <input type="text" name="name" value="{{ $venue->name }}" required class="input input-bordered w-full">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-black uppercase text-muted-foreground">Address</label>
                    <input type="text" name="address" value="{{ $venue->address }}" class="input input-bordered w-full">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-black uppercase text-muted-foreground">Total Capacity</label>
                        <input type="number" name="capacity" value="{{ $venue->capacity }}" min="0" class="input input-bordered w-full">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-black uppercase text-muted-foreground">Update Seat Map Image</label>
                        <input type="file" name="seat_map_image" class="file-input file-input-bordered w-full" accept="image/*">
                        @if($venue->seat_map_image)
                            <div class="mt-2 text-xs flex items-center gap-2 text-primary font-medium">
                                <span class="icon-[lucide--image] size-4"></span>
                                Current: {{ basename($venue->seat_map_image) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-black uppercase text-muted-foreground">Seat Numbers (Optional Mapping)</label>
                    <textarea name="seat_numbers" class="textarea textarea-bordered w-full h-32">{{ is_array($venue->seat_numbers) ? implode(', ', $venue->seat_numbers) : '' }}</textarea>
                    <p class="text-xs text-muted-foreground">If provided, attendees must select one of these seats during checkout.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.venues.index') }}" class="btn btn-ghost px-8">Cancel</a>
            <button type="submit" class="btn btn-primary px-10 shadow-xl shadow-primary/30">Save Changes</button>
        </div>
    </form>
</div>
@endsection
