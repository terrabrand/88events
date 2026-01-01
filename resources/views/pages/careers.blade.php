@extends('layouts.public')

@section('title', 'Careers')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">Join Our Team</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>At {{ config('app.name') }}, we're building the future of live events. We're always looking for talented individuals who are passionate about technology and community.</p>
            
            <div class="bg-card border border-border rounded-xl p-8 mt-8 text-center">
                <h3 class="text-2xl font-bold text-foreground mb-4">No Open Positions</h3>
                <p>We usually don't have open positions at the moment, but we're always happy to hear from talented people. Check back soon!</p>
            </div>
        </div>
    </div>
</div>
@endsection
