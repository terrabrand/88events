@extends('layouts.public')

@section('title', 'Cookie Policy')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">Cookie Policy</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>Last updated: {{ date('F d, Y') }}</p>
            <p>This Cookie Policy explains how {{ config('app.name') }} uses cookies and similar technologies to recognize you when you visit our website.</p>
            
            <h2 class="text-foreground">What are cookies?</h2>
            <p>Cookies are small data files that are placed on your computer or mobile device when you visit a website. Cookies are widely used by website owners in order to make their websites work, or to work more efficiently, as well as to provide reporting information.</p>

            <h2 class="text-foreground">Why do we use cookies?</h2>
            <p>We use first-party and third-party cookies for several reasons. Some cookies are required for technical reasons in order for our Website to operate, and we refer to these as "essential" or "strictly necessary" cookies. Other cookies also enable us to track and target the interests of our users to enhance the experience on our Online Properties.</p>
        </div>
    </div>
</div>
@endsection
