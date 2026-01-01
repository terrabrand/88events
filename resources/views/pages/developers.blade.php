@extends('layouts.public')

@section('title', 'Developers')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">Developers</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>Build on top of {{ config('app.name') }}. Integrate ticketing, event data, and check-in capabilities into your own applications.</p>
            
            <div class="bg-card border border-border rounded-xl p-8 mt-8 text-center">
                <span class="icon-[lucide--code-2] size-12 text-primary mb-4"></span>
                <h3 class="text-2xl font-bold text-foreground mb-4">API Documentation</h3>
                <p class="mb-6">Explore our REST API to manage events, attendees, and orders programmatically.</p>
                <button class="btn btn-primary" disabled>Coming Soon</button>
            </div>
        </div>
    </div>
</div>
@endsection
