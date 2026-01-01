@extends('layouts.public')

@section('title', 'Press')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">Press & Media</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>For press inquiries, brand assets, and interview requests, please contact our media team.</p>
            
            <div class="bg-card border border-border rounded-xl p-8 mt-8">
                <h3 class="text-xl font-bold text-foreground mb-2">Media Contact</h3>
                <p>Email: press@example.com</p>
            </div>
            
            <h2 class="text-foreground mt-12">Latest News</h2>
            <p>Check back soon for the latest company updates and announcements.</p>
        </div>
    </div>
</div>
@endsection
