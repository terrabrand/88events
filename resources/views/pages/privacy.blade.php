@extends('layouts.public')

@section('title', 'Privacy Policy')

@section('public-content')
<div class="bg-background py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black mb-8">Privacy Policy</h1>
        <div class="prose prose-lg max-w-none text-muted-foreground">
            <p>Last updated: {{ date('F d, Y') }}</p>
            <p>{{ config('app.name') }} ("us", "we", or "our") operates the website (the "Service"). This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service and the choices you have associated with that data.</p>
            
            <h2 class="text-foreground">Information Collection and Use</h2>
            <p>We collect several different types of information for various purposes to provide and improve our Service to you.</p>

            <h2 class="text-foreground">Types of Data Collected</h2>
            <h3>Personal Data</h3>
            <p>While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you ("Personal Data"). Personally identifiable information may include, but is not limited to: Email address, First name and last name, Phone number, Address, State, Province, ZIP/Postal code, City, Cookies and Usage Data.</p>
        </div>
    </div>
</div>
@endsection
