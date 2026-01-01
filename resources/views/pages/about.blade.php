@extends('layouts.public')

@section('title', 'About Us')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">About {{ config('app.name') }}</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>Welcome to {{ config('app.name') }}. We are dedicated to connecting people through unforgettable live experiences. From music festivals and conferences to local workshops and charity events, our platform empowers organizers to create, promote, and sell tickets with ease.</p>
            
            <h2 class="text-foreground">Our Mission</h2>
            <p>To bring the world together through live experiences.</p>

            <h2 class="text-foreground">Who We Are</h2>
            <p>We are a team of passionate event enthusiasts, developers, and designers building the next generation of event technology. We believe in the power of gathering and the magic that happens when people come together.</p>
        </div>
    </div>
</div>
@endsection
