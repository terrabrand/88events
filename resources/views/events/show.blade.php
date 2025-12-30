@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        
        <!-- Header -->
        <div class="relative h-64 w-full overflow-hidden rounded-xl border border-border bg-muted">
            @if($event->cover_image_path)
                <img src="{{ asset('storage/' . $event->cover_image_path) }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center bg-muted text-muted-foreground">
                    <span class="text-4xl font-bold opacity-20">NO IMAGE</span>
                </div>
            @endif
            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-6 text-white">
                <div class="flex items-end justify-between">
                    <div>
                        <div class="flex gap-2 mb-2">
                             <div class="inline-flex items-center rounded-full border border-transparent bg-primary text-primary-foreground px-2.5 py-0.5 text-xs font-semibold capitalize">{{ $event->location_type }}</div>
                             @if($event->category)
                                <div class="inline-flex items-center rounded-full border border-transparent bg-secondary text-secondary-foreground px-2.5 py-0.5 text-xs font-semibold capitalize">{{ $event->category->name }}</div>
                             @endif
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight lg:text-5xl">{{ $event->title }}</h1>
                        <p class="mt-1 opacity-90 flex items-center gap-2">
                            <span class="icon-[tabler--calendar] size-5"></span>
                            {{ $event->start_date->format('l, F j, Y \a\t h:i A') }}
                        </p>
                    </div>
                    @if(Auth::id() == $event->organizer_id)
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm">Edit Event</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                
                <!-- Live Stream Section -->
                @if($event->streaming_url && ($event->location_type == 'virtual' || $event->location_type == 'hybrid'))
                    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
                        <div class="p-0">
                            <div class="bg-black aspect-video flex items-center justify-center text-white">
                                @if(now() >= $event->start_date || Auth::id() == $event->organizer_id)
                                    <!-- Simple iframe for demonstration depending on URL structure -->
                                    <div class="text-center p-10">
                                        <h3 class="text-2xl font-bold mb-4">Live Stream Access</h3>
                                        <p class="mb-6">Click below to join the secure stream.</p>
                                        <a href="{{ $event->streaming_url }}" target="_blank" class="btn btn-error btn-lg">
                                            <span class="icon-[tabler--player-play] size-6"></span>
                                            Watch Live
                                        </a>
                                        <p class="mt-4 text-xs opacity-50">Embed player would load here if supported.</p>
                                    </div>
                                @else
                                    <div class="text-center p-10">
                                        <span class="icon-[tabler--clock] size-12 mb-4 opacity-50"></span>
                                        <h3 class="text-xl font-bold">Event has not started yet</h3>
                                        <p>Stream will become available on {{ $event->start_date->format('M d, h:i A') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight border-b border-border pb-4 mb-4">About this Event</h3>
                        <div class="prose max-w-none text-muted-foreground">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                         <div class="flex justify-between items-center border-b border-border pb-4 mb-4">
                            <h3 class="text-xl font-semibold leading-none tracking-tight">Reviews</h3>
                            @role('attendee')
                                <button class="btn btn-sm btn-outline border-border hover:bg-muted" onclick="document.getElementById('review_modal').showModal()">Write Review</button>
                            @endrole
                         </div>

                         <div class="flex flex-col gap-6">
                            @forelse($event->reviews()->where('is_approved', true)->latest()->take(5)->get() as $review)
                                <div class="border-b border-border last:border-0 pb-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex text-orange-400">
                                            @for($i=1; $i<=5; $i++)
                                                <span class="icon-[tabler--star{{ $i <= $review->rating ? '-filled' : '' }}] size-4"></span>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-semibold">{{ $review->user->name }}</span>
                                        <span class="text-xs text-muted-foreground">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-muted-foreground">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <div class="text-center text-muted-foreground opacity-50 py-4">No reviews yet.</div>
                            @endforelse
                         </div>
                    </div>
                </div>

                @role('attendee')
                <div class="text-center">
                    <button class="btn btn-ghost btn-xs text-destructive hover:bg-destructive/10" onclick="document.getElementById('report_modal').showModal()">
                        <span class="icon-[tabler--flag] size-3"></span>
                        Report this Event
                    </button>
                </div>
                @endrole
            </div>

            <!-- Modals -->
            <dialog id="review_modal" class="modal">
                <div class="modal-box bg-card text-card-foreground border border-border">
                    <h3 class="font-bold text-lg">Write a Review</h3>
                    <form action="{{ route('reviews.store', $event) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="form-control mb-4">
                            <label class="label"><span class="label-text text-foreground">Rating</span></label>
                             <div class="rating">
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="1" />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="2" />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="3" />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="4" />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="5" checked />
                            </div>
                        </div>
                        <div class="form-control mb-4">
                            <label class="label"><span class="label-text text-foreground">Comment</span></label>
                            <textarea name="comment" class="textarea textarea-bordered bg-background text-foreground" placeholder="Share your experience..."></textarea>
                        </div>
                        <div class="modal-action">
                            <button type="button" class="btn" onclick="document.getElementById('review_modal').close()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </dialog>

            <dialog id="report_modal" class="modal">
                <div class="modal-box bg-card text-card-foreground border border-border">
                    <h3 class="font-bold text-lg text-destructive">Report Event</h3>
                    <form action="{{ route('reports.store', $event) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="form-control mb-4">
                            <label class="label"><span class="label-text text-foreground">Reason</span></label>
                            <select name="reason" class="select select-bordered w-full bg-background text-foreground">
                                <option>Inappropriate Content</option>
                                <option>Scam / Fraud</option>
                                <option>Incorrect Information</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="form-control mb-4">
                            <label class="label"><span class="label-text text-foreground">Details (Optional)</span></label>
                            <textarea name="details" class="textarea textarea-bordered bg-background text-foreground"></textarea>
                        </div>
                        <div class="modal-action">
                            <button type="button" class="btn" onclick="document.getElementById('report_modal').close()">Cancel</button>
                            <button type="submit" class="btn btn-error">Submit Report</button>
                        </div>
                    </form>
                </div>
            </dialog>

            <!-- Sidebar -->
            <div class="flex flex-col gap-6">
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg mb-4">Event Details</h3>
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start gap-3">
                                <span class="icon-[tabler--map-pin] size-5 mt-1 text-primary"></span>
                                <div>
                                    <span class="font-medium block">Location</span>
                                    <span class="text-sm text-muted-foreground">
                                        @if($event->location_type == 'virtual')
                                            Online Event
                                        @elseif($event->venue)
                                            {{ $event->venue->name }}<br>
                                            <span class="text-xs">{{ $event->venue->address }}</span>
                                        @else
                                            {{ $event->venue_address ?? 'TBA' }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            @if($event->venue?->seat_map_image)
                                <div class="mt-4">
                                    <span class="text-xs font-black uppercase text-muted-foreground mb-2 block">Venue Seat Map</span>
                                    <div class="rounded-lg border border-border overflow-hidden bg-muted/50 p-1">
                                        <img src="{{ asset('storage/' . $event->venue->seat_map_image) }}" alt="Seat Map" class="w-full h-auto cursor-zoom-in hover:opacity-90 transition" onclick="window.open(this.src)">
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-start gap-3">
                                <span class="icon-[tabler--ticket] size-5 mt-1 text-primary"></span>
                                <div>
                                    <span class="font-medium block">Tickets</span>
                                    <span class="text-sm text-muted-foreground">
                                        @if($event->ticketTypes->count() > 0)
                                            Available
                                        @else
                                            Not available yet
                                        @endif
                                    </span>
                                </div>
                            </div>

                             <div class="flex items-start gap-3">
                                <span class="icon-[tabler--user] size-5 mt-1 text-primary"></span>
                                <div>
                                    <span class="font-medium block">Organizer</span>
                                    <span class="text-sm text-muted-foreground">{{ $event->organizer->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="my-6 border-t border-border"></div>
                        
                        @if($event->ticketTypes->count() > 0)
                            <form action="{{ route('tickets.purchase', $event) }}" method="POST">
                                @csrf
                                <div class="form-control w-full mb-3">
                                    <label class="label"><span class="label-text text-foreground font-medium">Select Ticket</span></label>
                                    <select name="ticket_type_id" class="select select-bordered bg-background text-foreground w-full" required>
                                        @foreach($event->ticketTypes as $type)
                                            <option value="{{ $type->id }}" {{ $type->available_quantity < 1 ? 'disabled' : '' }}>
                                                {{ $type->name }} - ${{ number_format($type->price, 2) }} 
                                                ({{ $type->available_quantity }} left)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(!$event->has_seat_mapping)
                                    <div class="form-control w-full mb-4">
                                        <label class="label"><span class="label-text text-foreground font-medium">Quantity</span></label>
                                        <select name="quantity" class="select select-bordered bg-background text-foreground w-full">
                                            @foreach(range(1, 5) as $q)
                                                <option value="{{ $q }}">{{ $q }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="quantity" value="1">
                                @endif

                                @if($event->has_seat_mapping && $event->venue?->seat_numbers)
                                    <div class="form-control w-full mb-4" x-data="{ selectedSeat: '' }">
                                        <label class="label"><span class="label-text text-foreground font-medium text-primary">Seat Selection</span></label>
                                        
                                        <div class="flex flex-col gap-2">
                                            <input type="hidden" name="seat_number" x-model="selectedSeat" required>
                                            
                                            <button type="button" class="btn btn-outline btn-primary w-full gap-2 border-dashed h-auto py-3" onclick="seat_selection_modal.showModal()">
                                                <span class="icon-[lucide--armchair] size-5"></span>
                                                <div class="text-left">
                                                    <span class="block text-sm" x-text="selectedSeat ? 'Seat Selected: ' + selectedSeat : 'Choose Your Seat'">Choose Your Seat</span>
                                                    <span class="block text-[10px] opacity-70" x-show="!selectedSeat">Click to view seat map and availability</span>
                                                </div>
                                            </button>

                                            @error('seat_number')
                                                <span class="text-xs text-error mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Seat Selection Modal --}}
                                        <dialog id="seat_selection_modal" class="modal modal-bottom sm:modal-middle">
                                            <div class="modal-box max-w-4xl bg-card border border-border p-0 overflow-hidden">
                                                <div class="p-6 border-b border-border flex items-center justify-between bg-muted/30">
                                                    <div class="flex items-center gap-3">
                                                        <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                                            <span class="icon-[lucide--armchair] size-6"></span>
                                                        </div>
                                                        <div>
                                                            <h3 class="font-bold text-lg">Select Your Seat</h3>
                                                            <p class="text-xs text-muted-foreground">{{ $event->venue->name }}</p>
                                                        </div>
                                                    </div>
                                                    <form method="dialog">
                                                        <button class="btn btn-sm btn-circle btn-ghost"><span class="icon-[lucide--x] size-5"></span></button>
                                                    </form>
                                                </div>

                                                <div class="grid grid-cols-1 lg:grid-cols-2">
                                                    {{-- Left: Seat Map Image --}}
                                                    <div class="p-6 bg-muted/10 border-r border-border">
                                                        <span class="text-[10px] font-black uppercase text-muted-foreground mb-4 block tracking-wider">Venue Layout Reference</span>
                                                        @if($event->venue->seat_map_image)
                                                            <div class="rounded-xl border border-border/50 overflow-hidden bg-white p-2 shadow-inner">
                                                                <img src="{{ asset('storage/' . $event->venue->seat_map_image) }}" alt="Seat Map" class="w-full h-auto object-contain max-h-[400px]">
                                                            </div>
                                                        @else
                                                            <div class="aspect-video w-full flex flex-col items-center justify-center rounded-xl border border-dashed border-border bg-muted/30">
                                                                <span class="icon-[lucide--image-off] size-12 text-muted-foreground/20 mb-2"></span>
                                                                <span class="text-xs text-muted-foreground">No map image available</span>
                                                            </div>
                                                        @endif
                                                        <div class="mt-4 flex flex-wrap gap-4 text-[10px] font-bold uppercase">
                                                            <div class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-primary shadow-sm shadow-primary/20"></span> Available</div>
                                                            <div class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-muted border border-border"></span> Occupied</div>
                                                            <div class="flex items-center gap-1.5"><span class="size-3 rounded-sm bg-success ring-2 ring-success/20 ring-offset-2 ring-offset-card"></span> Your Pick</div>
                                                        </div>
                                                    </div>

                                                    {{-- Right: Seat Grid --}}
                                                    <div class="p-6 flex flex-col h-[500px]">
                                                        <span class="text-[10px] font-black uppercase text-muted-foreground mb-4 block tracking-wider">Available Seats</span>
                                                        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                                                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                                                @foreach($event->venue->seat_numbers as $seat)
                                                                    @php $isOccupied = in_array($seat, $occupiedSeats); @endphp
                                                                    <button type="button" 
                                                                        class="group relative flex flex-col items-center justify-center aspect-square rounded-lg border transition-all duration-200"
                                                                        :class="{
                                                                            'bg-muted text-muted-foreground cursor-not-allowed opacity-50': {{ $isOccupied ? 'true' : 'false' }},
                                                                            'bg-success text-white border-success shadow-lg shadow-success/20 scale-95': selectedSeat === '{{ $seat }}',
                                                                            'bg-card hover:bg-primary/5 hover:border-primary border-border text-foreground': !{{ $isOccupied ? 'true' : 'false' }} && selectedSeat !== '{{ $seat }}'
                                                                        }"
                                                                        @click="{{ $isOccupied ? '' : "selectedSeat = '" . $seat . "'; seat_selection_modal.close()" }}"
                                                                        {{ $isOccupied ? 'disabled' : '' }}>
                                                                        <span class="text-[10px] font-bold">{{ $seat }}</span>
                                                                        @if($isOccupied)
                                                                            <span class="icon-[lucide--lock] size-2.5 absolute top-1 right-1 opacity-30"></span>
                                                                        @endif
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-6 pt-4 border-t border-border flex items-center justify-between">
                                                            <div x-show="selectedSeat">
                                                                <span class="text-xs text-muted-foreground">Current Selection:</span>
                                                                <div class="font-black text-primary text-xl" x-text="selectedSeat"></div>
                                                            </div>
                                                            <div x-show="!selectedSeat" class="text-xs text-muted-foreground italic">
                                                                Select a seat to continue
                                                            </div>
                                                            <button type="button" class="btn btn-primary btn-sm px-6" x-show="selectedSeat" onclick="seat_selection_modal.close()">
                                                                Confirm
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop">
                                                <button>close</button>
                                            </form>
                                        </dialog>
                                    </div>
                                @endif
                                <div class="form-control w-full mb-4">
                                    <label class="label"><span class="label-text text-foreground font-medium">Discount Code (Optional)</span></label>
                                    <input type="text" name="coupon_code" class="input input-bordered bg-background text-foreground w-full" placeholder="Promo Code">
                                </div>
                                <div class="form-control w-full mb-4">
                                    <label class="label"><span class="label-text text-foreground font-medium">Payment Method</span></label>
                                    <div class="grid grid-cols-1 gap-2 mt-1">
                                        @php
                                            $gateways = [
                                                'stripe' => ['name' => 'Stripe', 'icon' => 'brand-stripe'],
                                                'paypal' => ['name' => 'PayPal', 'icon' => 'brand-paypal'],
                                                'paystack' => ['name' => 'Paystack', 'icon' => 'credit-card'],
                                                'razorpay' => ['name' => 'Razorpay', 'icon' => 'credit-card'],
                                                'bank_transfer' => ['name' => 'Bank Transfer', 'icon' => 'building-bank'],
                                                'cash_on_hand' => ['name' => 'Cash on Hand', 'icon' => 'cash'],
                                            ];
                                            $anyEnabled = false;
                                        @endphp

                                        @foreach($gateways as $id => $info)
                                            @if(\App\Models\Setting::get($id . '_enabled') == '1')
                                                @php $anyEnabled = true; @endphp
                                                <label class="flex items-center gap-3 p-3 rounded-lg border border-border bg-background cursor-pointer hover:bg-muted/50 transition">
                                                    <input type="radio" name="payment_method" value="{{ $id }}" class="radio radio-primary radio-sm" required>
                                                    <span class="icon-[tabler--{{ $info['icon'] }}] size-5 text-muted-foreground"></span>
                                                    <span class="text-sm font-medium">{{ $info['name'] }}</span>
                                                </label>
                                            @endif
                                        @endforeach

                                        @if(!$anyEnabled)
                                            <p class="text-xs text-error italic">No payment methods enabled by admin.</p>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-full shadow" {{ !$anyEnabled ? 'disabled' : '' }}>Buy Tickets</button>
                            </form>
                        @else
                            <button class="btn btn-disabled w-full" disabled>Tickets Not Available</button>
                        @endif
                    </div>
                </div>

                @if(Auth::id() == $event->organizer_id)
                    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
                        <div class="p-6">
                             <h3 class="font-semibold text-lg mb-4">Organizer Tools</h3>
                             <a href="{{ route('events.export', $event) }}" class="btn btn-outline btn-block border-border hover:bg-muted text-foreground">
                                <span class="icon-[tabler--file-download] size-5"></span>
                                Export Attendees (CSV)
                             </a>
                        </div>
                    </div>
                @endif
            </div>
    </div>
</div>
@endsection
