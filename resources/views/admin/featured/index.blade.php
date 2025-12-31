@extends('layouts.app')

@section('title', 'Manage Featured Content')

@section('content')
<div class="mx-auto max-w-6xl space-y-8 pb-20">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-foreground">Featured Content</h1>
            <p class="text-muted-foreground mt-1">Manage the hero section slideshow on the homepage.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.add_event_modal.showModal()" class="btn btn-primary shadow-lg shadow-primary/20">
                <span class="icon-[lucide--ticket] size-4 mr-2"></span>
                Feature an Event
            </button>
            <button onclick="window.add_custom_modal.showModal()" class="btn btn-secondary shadow-lg shadow-secondary/20">
                <span class="icon-[lucide--plus] size-4 mr-2"></span>
                Add Custom Slide
            </button>
        </div>
    </div>

    {{-- Items List --}}
    <div class="bg-card rounded-2xl border border-border shadow-sm overflow-hidden">
        <div class="p-6 border-b border-border bg-muted/30">
            <h3 class="font-bold text-lg">Active Slides</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-lg">
                <thead>
                    <tr class="bg-muted/10">
                        <th class="w-16">Rank</th>
                        <th>Content</th>
                        <th>Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border" id="featured-items-body">
                    @forelse($items as $item)
                        <tr class="hover:bg-muted/50 transition-colors group" data-id="{{ $item->id }}">
                            <td class="font-black text-muted-foreground">#{{ $loop->iteration }}</td>
                            <td>
                                <div class="flex items-center gap-4">
                                    <div class="size-16 rounded-lg overflow-hidden bg-muted flex-shrink-0 border border-border">
                                        @if($item->type === 'event')
                                            <img src="{{ $item->event->cover_image_path ? asset('storage/' . $item->event->cover_image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2070' }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-foreground">
                                            @if($item->type === 'event')
                                                {{ $item->event->title }}
                                            @else
                                                {{ $item->title }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-muted-foreground line-clamp-1 max-w-sm">
                                            {{ $item->description ?: 'No description provided' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $item->type === 'event' ? 'badge-primary' : 'badge-secondary' }} badge-soft font-bold uppercase text-[10px]">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex justify-center">
                                    @if($item->is_active)
                                        <span class="badge badge-success badge-soft badge-xs px-2 py-2">Visible</span>
                                    @else
                                        <span class="badge badge-error badge-soft badge-xs px-2 py-2">Hidden</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.featured.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove this slide?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-square btn-ghost btn-sm text-destructive hover:bg-destructive/10">
                                            <span class="icon-[tabler--trash] size-5"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <span class="icon-[lucide--layers] size-12 mb-4"></span>
                                    <h4 class="font-bold text-xl">No featured content yet</h4>
                                    <p class="text-sm">Start by adding an event or a custom message to the homepage.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Event Modal --}}
    <dialog id="add_event_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
        <div class="modal-box max-w-md bg-card text-card-foreground border border-border shadow-2xl relative z-10 p-6 rounded-2xl" onclick="event.stopPropagation()">
            <h3 class="font-black text-2xl mb-2">Feature an Event</h3>
            <p class="text-muted-foreground text-sm mb-6">Select an existing event to show on the hero section.</p>
            
            <form action="{{ route('admin.featured.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="event">
                
                <div class="space-y-2">
                    <label class="text-sm font-bold px-1">Choose Event</label>
                    <select name="event_id" class="select select-bordered w-full h-10" required>
                        <option value="" disabled selected>Select an event...</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold px-1">Custom Description (Optional)</label>
                    <textarea name="description" class="textarea textarea-bordered w-full h-24" placeholder="If left blank, event description will be used..."></textarea>
                </div>

                <div class="modal-action flex gap-2 justify-end mt-6">
                    <button type="button" onclick="window.add_event_modal.close()" class="btn btn-ghost">Cancel</button>
                    <button type="submit" class="btn btn-primary shadow-lg shadow-primary/20">Add to Slideshow</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="fixed inset-0 w-full h-full cursor-pointer" onclick="window.add_event_modal.close()"></form>
    </dialog>

    {{-- Add Custom Modal --}}
    <dialog id="add_custom_modal" class="fixed inset-0 z-[999] w-screen h-screen max-w-none max-h-none m-0 p-0 bg-transparent backdrop:bg-black/50 backdrop:backdrop-blur-[2px] open:flex items-center justify-center">
        <div class="modal-box max-w-lg bg-card text-card-foreground border border-border shadow-2xl relative z-10 p-6 rounded-2xl" onclick="event.stopPropagation()">
            <h3 class="font-black text-2xl mb-2">Add Custom Slide</h3>
            <p class="text-muted-foreground text-sm mb-6">Create a manual banner for announcements or promotions.</p>
            
            <form action="{{ route('admin.featured.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="custom">
                
                <div class="space-y-2">
                    <label class="text-sm font-bold px-1 text-foreground">Headline</label>
                    <input type="text" name="title" class="input input-bordered w-full h-10" placeholder="e.g. New Year Extravaganza" required>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold px-1 text-foreground">Sub-headline</label>
                    <textarea name="description" class="textarea textarea-bordered w-full h-20" placeholder="A brief description of the announcement..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold px-1 text-foreground">Button Text</label>
                        <input type="text" name="button_text" class="input input-bordered w-full h-10" placeholder="e.g. Explore Now">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold px-1 text-foreground">Link URL</label>
                        <input type="text" name="link_url" class="input input-bordered w-full h-10" placeholder="e.g. /events/nye-2025" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold px-1 text-foreground">Background Image</label>
                    <input type="file" name="image" class="file-input file-input-bordered w-full h-10" accept="image/*" required>
                    <p class="text-[10px] text-muted-foreground px-1">Recommended size: 1920x800px</p>
                </div>

                <div class="modal-action flex gap-2 justify-end mt-6">
                    <button type="button" onclick="window.add_custom_modal.close()" class="btn btn-ghost">Cancel</button>
                    <button type="submit" class="btn btn-secondary shadow-lg shadow-secondary/20">Create Slide</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="fixed inset-0 w-full h-full cursor-pointer" onclick="window.add_custom_modal.close()"></form>
    </dialog>
</div>
@endsection
