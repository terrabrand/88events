@extends('layouts.app')

@section('title', 'Promote Event')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('organizer.promotions.index') }}" class="btn btn-ghost btn-circle hover:bg-muted text-muted-foreground hover:text-foreground transition-colors">
            <span class="icon-[tabler--arrow-left] size-6"></span>
        </a>
        <div>
            <h2 class="text-3xl font-black tracking-tight text-foreground">Promote Event</h2>
            <p class="text-muted-foreground mt-1 text-lg">Select a package to boost your event's reach and visibility.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error shadow-sm">
            <span class="icon-[tabler--alert-circle] size-5"></span>
            <div class="flex flex-col">
                <span class="font-bold">Please correct the following errors:</span>
                <span class="text-sm opacity-90">{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('organizer.promotions.store') }}" method="POST" id="promotionForm" class="space-y-6">
                @csrf
                
                <!-- 1. Select Event -->
                <div class="card bg-card border border-border shadow-sm overflow-hidden">
                    <div class="card-header border-b border-border bg-muted/20 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center size-8 rounded-full bg-primary text-primary-foreground font-bold text-sm">1</div>
                            <h3 class="font-bold text-lg">Select Event</h3>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-muted-foreground mb-1">Which event do you want to promote?</span>
                            </label>
                            <select name="event_id" class="select select-bordered w-full h-12 focus:ring-2 focus:ring-primary/20 transition-all bg-muted/30 focus:bg-card" required>
                                <option value="" disabled selected>Choose an event...</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->title }} ({{ $event->start_date->format('M d, Y') }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Select Package -->
                <div class="card bg-card border border-border shadow-sm overflow-hidden">
                    <div class="card-header border-b border-border bg-muted/20 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center size-8 rounded-full bg-primary text-primary-foreground font-bold text-sm">2</div>
                            <h3 class="font-bold text-lg">Choose Package</h3>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($packages as $pkg)
                                <label class="relative flex items-center p-5 rounded-2xl border-2 border-border cursor-pointer hover:border-primary/50 hover:bg-muted/20 transition-all group has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:shadow-md">
                                    <input type="radio" name="ad_package_id" value="{{ $pkg->id }}" class="radio radio-primary absolute left-6 top-6" data-price="{{ $pkg->price }}" required onclick="updateSummary()">
                                    
                                    <div class="pl-10 w-full">
                                        <div class="flex justify-between items-start w-full">
                                            <div>
                                                <div class="font-bold text-lg group-has-[:checked]:text-primary transition-colors">{{ $pkg->name }}</div>
                                                <div class="badge badge-outline badge-sm mt-2 text-muted-foreground border-border bg-card">
                                                    <span class="icon-[tabler--clock] size-3 mr-1"></span>
                                                    {{ $pkg->duration_days }} Days
                                                </div>
                                            </div>
                                            <div class="text-2xl font-black tracking-tight text-foreground">
                                                ${{ number_format($pkg->price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Selection Indicator -->
                                    <div class="absolute inset-0 border-2 border-primary rounded-2xl opacity-0 scale-95 group-has-[:checked]:opacity-100 group-has-[:checked]:scale-100 transition-all pointer-events-none"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 3. Schedule -->
                <div class="card bg-card border border-border shadow-sm overflow-hidden">
                    <div class="card-header border-b border-border bg-muted/20 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center size-8 rounded-full bg-primary text-primary-foreground font-bold text-sm">3</div>
                            <h3 class="font-bold text-lg">Start Date</h3>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-muted-foreground mb-1">When should the promotion begin?</span>
                            </label>
                            <div class="relative max-w-sm">
                                <span class="icon-[tabler--calendar] absolute left-3 top-3.5 size-5 text-muted-foreground"></span>
                                <input type="date" name="start_date" class="input input-bordered w-full h-12 pl-10 focus:ring-2 focus:ring-primary/20 transition-all bg-muted/30 focus:bg-card" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <p class="text-xs text-muted-foreground mt-3 flex items-center gap-1">
                                <span class="icon-[tabler--info-circle] size-3"></span>
                                Promotion runs from 00:00 on the start date
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="card bg-card border border-border shadow-xl sticky top-6 overflow-hidden">
                <div class="card-header bg-gradient-to-r from-primary/10 to-transparent p-6 border-b border-border/50">
                    <h3 class="card-title text-xl font-black tracking-tight">Summary</h3>
                </div>
                <div class="card-body p-6">
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center p-3 rounded-lg bg-muted/30">
                            <span class="text-sm font-medium text-muted-foreground">Current Wallet</span>
                            <span class="font-bold text-foreground">${{ number_format(auth()->user()->credits, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm px-1">
                            <span class="text-muted-foreground">Package Cost</span>
                            <span class="font-bold text-error" id="summaryCost">$0.00</span>
                        </div>
                        
                        <div class="divider my-2"></div>
                        
                        <div class="flex justify-between items-center px-1">
                            <span class="font-bold text-foreground">Remaining</span>
                            <span class="font-black text-xl text-success" id="summaryRemaining">${{ number_format(auth()->user()->credits, 2) }}</span>
                        </div>
                    </div>

                    <div id="insufficientFunds" class="alert alert-error text-sm py-3 px-4 shadow-sm hidden mb-4 rounded-xl">
                        <span class="icon-[tabler--coin-off] size-5"></span>
                        <span class="font-semibold">Insufficient credits</span>
                    </div>

                    <div class="space-y-3">
                        <button type="submit" form="promotionForm" id="submitBtn" class="btn btn-primary w-full shadow-lg shadow-primary/25 font-bold h-12 rounded-xl transition-transform active:scale-95">
                            Confirm Payment
                        </button>
                        <a href="{{ route('organizer.credits.index') }}" class="btn btn-outline border-border hover:bg-muted text-muted-foreground hover:text-foreground w-full font-medium rounded-xl">
                            <span class="icon-[tabler--wallet] mr-2 size-4"></span>
                            Top up Wallet
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-muted/20 p-4 text-center">
                    <p class="text-xs text-muted-foreground">By confirming, you agree to our promotion terms.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const userCredits = {{ auth()->user()->credits }};

    function updateSummary() {
        const selected = document.querySelector('input[name="ad_package_id"]:checked');
        if (!selected) return;

        const price = parseFloat(selected.dataset.price);
        document.getElementById('summaryCost').innerText = '-$' + price.toFixed(2);

        const remaining = userCredits - price;
        const remainingEl = document.getElementById('summaryRemaining');
        const warning = document.getElementById('insufficientFunds');
        const btn = document.getElementById('submitBtn');

        remainingEl.innerText = '$' + remaining.toFixed(2);
        
        if (remaining < 0) {
            remainingEl.classList.remove('text-success');
            remainingEl.classList.add('text-error');
            warning.classList.remove('hidden');
            btn.disabled = true;
            btn.innerHTML = `<span class="icon-[tabler--lock] mr-2"></span> Insufficient Funds`;
            btn.classList.add('btn-error');
        } else {
            remainingEl.classList.remove('text-error');
            remainingEl.classList.add('text-success');
            warning.classList.add('hidden');
            btn.disabled = false;
            btn.innerHTML = `Confirm Payment <span class="icon-[tabler--check] ml-2"></span>`;
            btn.classList.remove('btn-error');
        }
    }
</script>
@endpush
@endsection
