@extends('layouts.guest')

@section('content')
    <div class="min-h-screen bg-background text-foreground flex flex-col">
        @include('components.public-header')

        <main class="flex-1">
            @yield('public-content')
        </main>

        @include('components.public-footer')
    </div>
@endsection
