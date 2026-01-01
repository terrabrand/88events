@extends('layouts.public')

@section('title', 'Pricing')

@section('public-content')
<div class="bg-background py-16 md:py-24">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black mb-4">Simple, Transparent Pricing</h1>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto">Choose the plan that fits your event needs. No hidden fees.</p>
        </div>

        {{-- Subscription Packages --}}
        @if($packages->isNotEmpty())
        <div class="mb-20">
            <h2 class="text-2xl font-bold mb-8 text-center text-primary">Organizer Subscriptions</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($packages as $package)
                <div class="card bg-card border border-border shadow-lg hover:shadow-xl transition-all h-full">
                    <div class="card-body p-8 flex flex-col h-full">
                        <h3 class="text-2xl font-bold mb-2">{{ $package->name }}</h3>
                        <div class="text-4xl font-black mb-6">
                            ${{ number_format($package->price, 2) }} <span class="text-lg font-medium text-muted-foreground">/mo</span>
                        </div>
                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-center gap-2">
                                <span class="icon-[lucide--check] text-primary"></span>
                                <span>{{ $package->sms_limit }} SMS Credits/mo</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="icon-[lucide--check] text-primary"></span>
                                <span>{{ $package->email_limit }} Email Credits/mo</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="icon-[lucide--check] text-primary"></span>
                                <span>Priority Support</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-primary w-full font-bold">Get Started</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Ad Packages --}}
        @if($adPackages->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold mb-8 text-center text-secondary">Event Promotion Boosts</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($adPackages as $package)
                <div class="card bg-card border border-secondary/20 shadow-lg hover:shadow-xl transition-all h-full">
                    <div class="card-body p-8 flex flex-col h-full">
                        <div class="badge badge-secondary mb-4">Boost Visibility</div>
                        <h3 class="text-2xl font-bold mb-2">{{ $package->name }}</h3>
                        <div class="text-4xl font-black mb-6">
                            ${{ number_format($package->price, 2) }}
                        </div>
                        <p class="text-muted-foreground mb-6">Run your promotion for <strong>{{ $package->duration_days }} days</strong></p>
                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-center gap-2">
                                <span class="icon-[lucide--trending-up] text-secondary"></span>
                                <span>Appears in "Promoted" Section</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="icon-[lucide--eye] text-secondary"></span>
                                <span>Higher Search Ranking</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-secondary w-full font-bold">Boost Event</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($packages->isEmpty() && $adPackages->isEmpty())
            <div class="text-center py-12">
                <p class="text-muted-foreground">Pricing information coming soon.</p>
            </div>
        @endif
    </div>
</div>
@endsection
