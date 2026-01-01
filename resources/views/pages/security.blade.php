@extends('layouts.public')

@section('title', 'Security')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">Security</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>Security is our top priority at {{ config('app.name') }}. We are committed to protecting your data and ensuring a safe platform for organizers and attendees.</p>
            
            <h2 class="text-foreground">Reporting Vulnerabilities</h2>
            <p>If you believe you have found a security vulnerability in our platform, please report it to us immediately at security@example.com.</p>

             <h2 class="text-foreground">Data Protection</h2>
            <p>We use industry-standard encryption to protect your personal information and payment details.</p>
        </div>
    </div>
</div>
@endsection
