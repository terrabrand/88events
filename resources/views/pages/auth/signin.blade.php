@extends('layouts.guest')

@section('title', 'Login - ' . config('app.name'))

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-background py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-card text-card-foreground p-10 rounded-2xl border border-border shadow-sm transition-all hover:shadow-md">
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-black text-foreground tracking-tight">Login to your account</h2>
                <p class="mt-2 text-sm text-muted-foreground">Enter your email below to login to your account</p>
            </div>
            
            <div class="mt-8 space-y-6">
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-bold text-foreground mb-1">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="block w-full rounded-lg border-input bg-input text-foreground shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 px-4 border" 
                               placeholder="m@example.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-bold text-foreground">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-muted-foreground hover:text-foreground underline">Forgot your password?</a>
                            @endif
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="block w-full rounded-lg border-input bg-input text-foreground shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 px-4 border">
                        @error('password')
                            <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-primary px-4 py-3 text-sm font-bold text-primary-foreground shadow hover:bg-primary/90 transition-all">
                            Login
                        </button>
                    </div>
                </form>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-border"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-card px-2 text-muted-foreground">Or continue with</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="{{ url('auth/google/redirect') }}" class="flex w-full items-center justify-center gap-3 rounded-lg border border-input bg-background px-4 py-3 text-sm font-bold text-foreground shadow-sm hover:bg-muted transition-all">
                        <span class="icon-[logos--google-icon] size-5"></span>
                        Login with Google
                    </a>
                    <a href="{{ url('auth/facebook/redirect') }}" class="flex w-full items-center justify-center gap-3 rounded-lg border border-input bg-[#1877F2] px-4 py-3 text-sm font-bold text-white shadow-sm hover:opacity-90 transition-all">
                        <span class="icon-[logos--facebook] size-5 text-white"></span>
                        Login with Facebook
                    </a>
                </div>

                <p class="text-center text-sm text-muted-foreground">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-bold text-foreground hover:underline">Sign up</a>
                </p>
            </div>
        </div>
    </div>
@endsection
