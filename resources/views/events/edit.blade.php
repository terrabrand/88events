@extends('layouts.app')



@section('content')
    <div class="mx-auto max-w-4xl space-y-8">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Edit Event</h2>
            <div class="flex gap-2">
                <a href="{{ route('organizer.guests.event', $event) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary/10 text-primary hover:bg-primary/20 h-10 px-4 py-2">
                    <span class="icon-[tabler--users-group] mr-2 size-4"></span>
                    Manage Guestlist
                </a>
                <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    <span class="icon-[tabler--arrow-left] mr-2 size-4"></span>
                    Back to Events
                </a>
            </div>
        </div>

        <!-- Event Details Form -->
        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="title">Title</label>
                            <input type="text" name="title" id="title" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('title', $event->title) }}" required>
                            @error('title') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="description">Description</label>
                        <textarea name="description" id="description" class="flex min-h-[120px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required>{{ old('description', $event->description) }}</textarea>
                        @error('description') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="start_date">Start Date & Time</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                            @error('start_date') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="end_date">End Date & Time</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required>
                            @error('end_date') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Type & Location -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="location_type">Event Type</label>
                        <select name="location_type" id="location_type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="physical" {{ old('location_type', $event->location_type) == 'physical' ? 'selected' : '' }}>Physical Venue</option>
                            <option value="virtual" {{ old('location_type', $event->location_type) == 'virtual' ? 'selected' : '' }}>Virtual / Online</option>
                            <option value="hybrid" {{ old('location_type', $event->location_type) == 'hybrid' ? 'selected' : '' }}>Hybrid (Both)</option>
                        </select>
                        @error('location_type') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="venue_address">Venue Address (Physical/Hybrid)</label>
                        <input type="text" name="venue_address" id="venue_address" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('venue_address', $event->venue_address) }}">
                        @error('venue_address') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        
                        <!-- Hidden Location Fields -->
                        <input type="hidden" name="venue_name" id="venue_name" value="{{ $event->venue ? $event->venue->name : '' }}">
                        <input type="hidden" name="venue_city" id="venue_city" value="{{ $event->venue ? $event->venue->city : '' }}">
                        <input type="hidden" name="venue_state" id="venue_state" value="{{ $event->venue ? $event->venue->state : '' }}">
                        <input type="hidden" name="venue_country" id="venue_country" value="{{ $event->venue ? $event->venue->country : '' }}">
                        <input type="hidden" name="venue_lat" id="venue_lat" value="{{ $event->venue ? $event->venue->lat : '' }}">
                        <input type="hidden" name="venue_lng" id="venue_lng" value="{{ $event->venue ? $event->venue->lng : '' }}">
                        <input type="hidden" name="venue_google_place_id" id="venue_google_place_id" value="{{ $event->venue ? $event->venue->google_place_id : '' }}">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="streaming_url">Streaming URL (Virtual/Hybrid)</label>
                        <input type="url" name="streaming_url" id="streaming_url" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('streaming_url', $event->streaming_url) }}" placeholder="https://youtube.com/live/...">
                    </div>

                    <div class="space-y-4 rounded-lg border border-border bg-muted/10 p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="venue_id">Linked Venue (Optional)</label>
                                <select name="venue_id" id="venue_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">No Venue Mapping</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }} data-has-seats="{{ $venue->seat_numbers ? 'true' : 'false' }}">
                                            {{ $venue->name }} {{ $venue->is_global ? '(Global)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="seat_mapping_container" class="space-y-4 {{ (old('venue_id', $event->venue_id) && $venues->find(old('venue_id', $event->venue_id))?->seat_numbers) ? '' : 'hidden' }}">
                                <div class="flex items-center justify-between pt-2">
                                    <div class="space-y-0.5">
                                        <label class="text-base font-medium" for="has_seat_mapping">Enable Seat Selection</label>
                                        <p class="text-sm text-muted-foreground">Attendees pick seats from the map.</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="hidden" name="has_seat_mapping" value="0">
                                        <input type="checkbox" name="has_seat_mapping" id="has_seat_mapping" value="1" {{ old('has_seat_mapping', $event->has_seat_mapping) ? 'checked' : '' }} class="peer h-4 w-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tax Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="tax_type">Tax Type</label>
                            <select name="tax_type" id="tax_type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="none" {{ old('tax_type', $event->tax_type) == 'none' ? 'selected' : '' }}>No Tax</option>
                                <option value="inclusive" {{ old('tax_type', $event->tax_type) == 'inclusive' ? 'selected' : '' }}>Inclusive (Price includes tax)</option>
                                <option value="exclusive" {{ old('tax_type', $event->tax_type) == 'exclusive' ? 'selected' : '' }}>Exclusive (Tax added at checkout)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="tax_rate">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" step="0.01" min="0" max="100" value="{{ old('tax_rate', $event->tax_rate) }}">
                        </div>
                    </div>

                    <!-- Promoter Settings -->
                    <div class="rounded-lg border border-border bg-muted/30 p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="space-y-0.5">
                                <label class="text-base font-medium" for="allow_promoters">Allow Promoters</label>
                                <p class="text-sm text-muted-foreground">Enable this to allow users to promote your event for a commission.</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="hidden" name="allow_promoters" value="0">
                                <input type="checkbox" name="allow_promoters" id="allow_promoters" value="1" {{ old('allow_promoters', $event->allow_promoters) ? 'checked' : '' }} class="peer h-4 w-4 shrink-0 rounded-sm border border-primary ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground">
                            </div>
                        </div>

                        <div id="promoter_options" class="{{ old('allow_promoters', $event->allow_promoters) ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="commission_type">Commission Type</label>
                                <select name="commission_type" id="commission_type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="percentage" {{ old('commission_type', $event->commission_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="flat" {{ old('commission_type', $event->commission_type) == 'flat' ? 'selected' : '' }}>Flat Rate (Fixed Amount)</option>
                                </select>
                                @error('commission_type') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="commission_rate">Commission Rate</label>
                                <input type="number" name="commission_rate" id="commission_rate" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" step="0.01" min="0" value="{{ old('commission_rate', $event->commission_rate) }}">
                                @error('commission_rate') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="cover_image">Cover Image</label>
                        @if($event->cover_image_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $event->cover_image_path) }}" alt="Current Cover" class="h-32 w-auto rounded-md object-cover ring-1 ring-border">
                            </div>
                        @endif
                        <input type="file" name="cover_image" id="cover_image" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" accept="image/*">
                        @error('cover_image') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                     <!-- Status -->
                     <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="status">Status</label>
                        <select name="status" id="status" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <!-- Assigned Scanners -->
                    <div class="space-y-4 pt-4 border-t border-border">
                        <h3 class="font-semibold leading-none tracking-tight">Assign Scanners</h3>
                        <p class="text-sm text-muted-foreground">Select users who are authorized to scan tickets for this event.</p>
                        
                        <input type="hidden" name="update_scanners" value="1">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($scanners as $scanner)
                                <label class="flex items-center space-x-3 rounded-md border border-input p-3 shadow-sm hover:bg-accent cursor-pointer transition-colors">
                                    <input type="checkbox" name="scanners[]" value="{{ $scanner->id }}" 
                                        @checked($event->scanners->contains($scanner->id))
                                        class="h-4 w-4 rounded border-primary text-primary focus:ring-primary shadow-sm">
                                    <span class="text-sm font-medium leading-none">{{ $scanner->name }} <span class="text-muted-foreground text-xs">({{ $scanner->email }})</span></span>
                                </label>
                            @endforeach
                            @if($scanners->isEmpty())
                                <div class="col-span-full text-sm text-muted-foreground italic">
                                    No users with 'scanner' role found. Please ensure users have the 'scanner' role assigned.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">Update Event</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ticket Management Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- List Ticket Types -->
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm col-span-2">
                <div class="p-6 pb-4">
                    <h3 class="font-semibold leading-none tracking-tight">Ticket Types</h3>
                    <p class="text-sm text-muted-foreground pt-1">Manage ticket types and pricing.</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-muted-foreground uppercase bg-muted/50">
                                <tr>
                                    <th class="px-4 py-3 font-medium">Name</th>
                                    <th class="px-4 py-3 font-medium">Price</th>
                                    <th class="px-4 py-3 font-medium">Stock</th>
                                    <th class="px-4 py-3 font-medium">Sold</th>
                                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($event->ticketTypes as $type)
                                    <tr class="border-b border-border hover:bg-muted/50 transition-colors last:border-0">
                                        <td class="px-4 py-3 font-medium">{{ $type->name }}</td>
                                        <td class="px-4 py-3">${{ number_format($type->price, 2) }}</td>
                                        <td class="px-4 py-3">{{ $type->quantity }}</td>
                                        <td class="px-4 py-3">{{ $type->quantity_sold }}</td>
                                        <td class="px-4 py-3 text-right">
                                            @if($type->quantity_sold == 0)
                                                <form action="{{ route('ticket-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Delete this ticket type?');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-transparent text-sm font-medium shadow-sm transition-colors hover:bg-destructive hover:text-destructive-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50">
                                                        <span class="icon-[tabler--trash] size-4"></span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center rounded-full border border-transparent bg-secondary text-secondary-foreground px-2.5 py-0.5 text-xs font-semibold">In Use</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-muted-foreground opacity-60">No ticket types defined yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Ticket Type Form -->
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm h-fit">
                <div class="p-6">
                    <h4 class="font-semibold leading-none tracking-tight mb-4">Add New Ticket Type</h4>
                    <form action="{{ route('ticket-types.store', $event) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Name</label>
                            <input type="text" name="name" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" placeholder="e.g. VIP" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Price</label>
                            <input type="number" name="price" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" step="0.01" min="0" placeholder="0.00" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Quantity</label>
                            <input type="number" name="quantity" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" min="1" placeholder="100" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Sales Start (Optional)</label>
                            <input type="datetime-local" name="sales_start_date" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Sales End (Optional)</label>
                            <input type="datetime-local" name="sales_end_date" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 w-full mt-2">Add Ticket Type</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Coupon Management Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- List Coupons -->
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm col-span-2">
                <div class="p-6 pb-4">
                    <h3 class="font-semibold leading-none tracking-tight">Coupons</h3>
                    <p class="text-sm text-muted-foreground pt-1">Manage discount coupons for this event.</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-muted-foreground uppercase bg-muted/50">
                                <tr>
                                    <th class="px-4 py-3 font-medium">Code</th>
                                    <th class="px-4 py-3 font-medium">Type</th>
                                    <th class="px-4 py-3 font-medium">Value</th>
                                    <th class="px-4 py-3 font-medium">Usage</th>
                                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($event->coupons as $coupon)
                                    <tr class="border-b border-border hover:bg-muted/50 transition-colors last:border-0">
                                        <td class="px-4 py-3 font-mono font-bold">{{ $coupon->code }}</td>
                                        <td class="px-4 py-3 capitalize">{{ $coupon->type }}</td>
                                        <td class="px-4 py-3">{{ $coupon->type == 'fixed' ? '$'.$coupon->amount : $coupon->amount.'%' }}</td>
                                        <td class="px-4 py-3">{{ $coupon->used_count }} {{ $coupon->usage_limit ? '/ '.$coupon->usage_limit : '' }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete this coupon?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-transparent text-sm font-medium shadow-sm transition-colors hover:bg-destructive hover:text-destructive-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50">
                                                    <span class="icon-[tabler--trash] size-4"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-muted-foreground opacity-60">No coupons created.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Coupon Form -->
            <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm h-fit">
                <div class="p-6">
                    <h4 class="font-semibold leading-none tracking-tight mb-4">Create Coupon</h4>
                    <form action="{{ route('coupons.store', $event) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Code</label>
                            <input type="text" name="code" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 uppercase" placeholder="SUMMER2024" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none">Type</label>
                                <select name="type" class="flex h-9 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="percent">Percent</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none">Value</label>
                                <input type="number" name="amount" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" step="0.01" min="0" required>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Usage Limit (Optional)</label>
                            <input type="number" name="usage_limit" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" min="1">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Valid Until (Optional)</label>
                            <input type="date" name="valid_until" class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 w-full mt-2">Create Coupon</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Debug Google Maps Loading
    function checkGoogleMapsLoaded() {
        if (typeof google === 'object' && typeof google.maps === 'object') {
            console.log('Google Maps API loaded successfully');
            initAutocomplete();
        } else {
            console.error('Google Maps API not loaded. Check your API key and network.');
        }
    }

    document.getElementById('allow_promoters').addEventListener('change', function() {
        document.getElementById('promoter_options').classList.toggle('hidden', !this.checked);
    });

    document.getElementById('venue_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const hasSeats = selectedOption.getAttribute('data-has-seats') === 'true';
        const container = document.getElementById('seat_mapping_container');
        
        container.classList.toggle('hidden', !hasSeats);
        if (!hasSeats) {
            document.getElementById('has_seat_mapping').checked = false;
        }
    });

    // Google Places Autocomplete
    function initAutocomplete() {
        const input = document.getElementById('venue_address');
        if (!input) {
            console.error('Venue address input not found');
            return;
        }

        const autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                console.warn('No details available for input: ' + place.name);
                return;
            }

            document.getElementById('venue_name').value = place.name;
            document.getElementById('venue_lat').value = place.geometry.location.lat();
            document.getElementById('venue_lng').value = place.geometry.location.lng();
            document.getElementById('venue_google_place_id').value = place.place_id;

            let addressComponents = place.address_components;
            let city = '';
            let state = '';
            let country = '';

            for (let i = 0; i < addressComponents.length; i++) {
                const types = addressComponents[i].types;
                if (types.includes('locality')) {
                    city = addressComponents[i].long_name;
                }
                if (types.includes('administrative_area_level_1')) {
                    state = addressComponents[i].long_name;
                }
                if (types.includes('country')) {
                    country = addressComponents[i].long_name;
                }
            }

            document.getElementById('venue_city').value = city;
            document.getElementById('venue_state').value = state;
            document.getElementById('venue_country').value = country;
            
            console.log('Venue details populated:', {name: place.name, city: city, state: state, country: country});
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=checkGoogleMapsLoaded" async defer></script>
@endpush
@endpush

