@extends('layouts.public')

@section('title', 'Terms of Service')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">Terms of Service</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>Last updated: {{ date('F d, Y') }}</p>
            <p>Please read these Terms of Service carefully before using our service.</p>
            
            <h2 class="text-foreground">1. Acceptance of Terms</h2>
            <p>By accessing or using {{ config('app.name') }}, you agree to be bound by these Terms. If you disagree with any part of the terms, then you may not access the Service.</p>

            <h2 class="text-foreground">2. Accounts</h2>
            <p>When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.</p>
            
            <h2 class="text-foreground">3. Events</h2>
            <p>Organizers are solely responsible for their events. {{ config('app.name') }} is a platform for facilitating the sale of tickets and does not organize or host the events listed on the Service.</p>
        </div>
    </div>
</div>
@endsection
