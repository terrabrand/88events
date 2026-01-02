@extends('layouts.app')



@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Create New Event</h2>
            <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                <span class="icon-[tabler--arrow-left] mr-2 size-4"></span>
                Back to Events
            </a>
        </div>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="title">Title</label>
                            <input type="text" name="title" id="title" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('title') }}" required>
                            @error('title') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="description">Description</label>
                        <textarea name="description" id="description" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" rows="5" required>{{ old('description') }}</textarea>
                        @error('description') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="start_date">Start Date & Time</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('start_date') }}" required>
                            @error('start_date') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="end_date">End Date & Time</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('end_date') }}" required>
                            @error('end_date') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Type & Location -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="location_type">Event Type</label>
                        <select name="location_type" id="location_type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="physical" {{ old('location_type') == 'physical' ? 'selected' : '' }}>Physical Venue</option>
                            <option value="virtual" {{ old('location_type') == 'virtual' ? 'selected' : '' }}>Virtual / Online</option>
                            <option value="hybrid" {{ old('location_type') == 'hybrid' ? 'selected' : '' }}>Hybrid (Both)</option>
                        </select>
                        @error('location_type') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="venue_address">Venue Address (Physical/Hybrid)</label>
                        <input type="text" name="venue_address" id="venue_address" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('venue_address') }}">
                        @error('venue_address') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="streaming_url">Streaming URL (Virtual/Hybrid)</label>
                        <input type="url" name="streaming_url" id="streaming_url" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('streaming_url') }}" placeholder="https://youtube.com/live/...">
                    </div>

                    <div class="space-y-4 rounded-lg border border-border bg-muted/10 p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="venue_id">Linked Venue (Optional)</label>
                                <select name="venue_id" id="venue_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">No Venue Mapping</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }} data-has-seats="{{ $venue->seat_numbers ? 'true' : 'false' }}">
                                            {{ $venue->name }} {{ $venue->is_global ? '(Global)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-muted-foreground">Select a pre-defined venue for mapping and capacity control.</p>
                            </div>

                            <div id="seat_mapping_container" class="space-y-4 {{ old('venue_id') && $venues->find(old('venue_id'))?->seat_numbers ? '' : 'hidden' }}">
                                <div class="flex items-center justify-between pt-2">
                                    <div class="space-y-0.5">
                                        <label class="text-base font-medium" for="has_seat_mapping">Enable Seat Selection</label>
                                        <p class="text-sm text-muted-foreground">Allows attendees to pick specific seat numbers.</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="hidden" name="has_seat_mapping" value="0">
                                        <input type="checkbox" name="has_seat_mapping" id="has_seat_mapping" value="1" {{ old('has_seat_mapping') ? 'checked' : '' }} class="peer h-4 w-4">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tax Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="tax_type">Tax Type</label>
                            <select name="tax_type" id="tax_type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="none" {{ old('tax_type') == 'none' ? 'selected' : '' }}>No Tax</option>
                                <option value="inclusive" {{ old('tax_type') == 'inclusive' ? 'selected' : '' }}>Inclusive (Price includes tax)</option>
                                <option value="exclusive" {{ old('tax_type') == 'exclusive' ? 'selected' : '' }}>Exclusive (Tax added at checkout)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="tax_rate">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" step="0.01" min="0" max="100" value="{{ old('tax_rate', 0) }}">
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
                                <input type="checkbox" name="allow_promoters" id="allow_promoters" value="1" {{ old('allow_promoters') ? 'checked' : '' }} class="peer h-4 w-4 shrink-0 rounded-sm border border-primary ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground">
                            </div>
                        </div>

                        <div id="promoter_options" class="{{ old('allow_promoters') ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="commission_type">Commission Type</label>
                                <select name="commission_type" id="commission_type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="percentage" {{ old('commission_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="flat" {{ old('commission_type') == 'flat' ? 'selected' : '' }}>Flat Rate (Fixed Amount)</option>
                                </select>
                                @error('commission_type') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none" for="commission_rate">Commission Rate</label>
                                <input type="number" name="commission_rate" id="commission_rate" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" step="0.01" min="0" value="{{ old('commission_rate', 0) }}">
                                @error('commission_rate') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="status">Status</label>
                        <select name="status" id="status" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="cover_image">Cover Image</label>
                        <input type="file" name="cover_image" id="cover_image" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" accept="image/*">
                        @error('cover_image') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">Create Event</button>
                    </div>
                </form>
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
