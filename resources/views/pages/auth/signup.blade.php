@extends('layouts.guest')

@section('title', 'Register - ' . config('app.name'))

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-background py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-card text-card-foreground p-10 rounded-2xl border border-border shadow-sm transition-all hover:shadow-md">
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-black text-foreground tracking-tight">Create an account</h2>
                <p class="mt-2 text-sm text-muted-foreground">Enter your details below to create your account</p>
            </div>

            <div class="mt-8 space-y-6">
                <form action="{{ route('register') }}" method="POST" class="space-y-5" x-data="{ role: 'attendee' }">
                    @csrf
                    
                    {{-- Account Type Selection --}}
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">I want to...</label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Attendee Option -->
                            <div class="relative">
                                <input type="radio" name="role" value="attendee" id="role_attendee" class="peer sr-only" x-model="role">
                                <label for="role_attendee" class="flex flex-col items-center justify-center p-4 bg-card border-2 border-border rounded-lg cursor-pointer hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center h-full group">
                                    <span class="icon-[lucide--ticket] size-6 mb-2 text-muted-foreground group-hover:text-primary peer-checked:text-primary"></span>
                                    <span class="text-sm font-bold text-foreground">Buy Tickets</span>
                                    <span class="text-xs text-muted-foreground mt-1">Discover & attend events</span>
                                </label>
                            </div>

                            <!-- Organizer Option -->
                            <div class="relative">
                                <input type="radio" name="role" value="organizer" id="role_organizer" class="peer sr-only" x-model="role">
                                <label for="role_organizer" class="flex flex-col items-center justify-center p-4 bg-card border-2 border-border rounded-lg cursor-pointer hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center h-full group">
                                    <span class="icon-[lucide--calendar] size-6 mb-2 text-muted-foreground group-hover:text-primary peer-checked:text-primary"></span>
                                    <span class="text-sm font-bold text-foreground">Host Events</span>
                                    <span class="text-xs text-muted-foreground mt-1">Create & manage events</span>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-bold text-foreground mb-1">Username</label>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                               class="block w-full rounded-lg border-input bg-input text-foreground shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 px-4 border" 
                               placeholder="johndoe" value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-foreground mb-1">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="block w-full rounded-lg border-input bg-input text-foreground shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 px-4 border" 
                               placeholder="m@example.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="role === 'attendee'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <label for="phone" class="block text-sm font-bold text-foreground mb-1">Phone Number</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" 
                               class="block w-full rounded-lg border-input bg-input text-foreground shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 px-4 border" 
                               placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                        <p class="mt-1 text-xs text-muted-foreground">We'll use this to send you ticket updates.</p>
                        @error('phone')
                            <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-foreground mb-1">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="block w-full rounded-lg border-input bg-input text-foreground shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 px-4 border">
                        @error('password')
                            <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-foreground mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="block w-full rounded-lg border-input bg-input text-foreground shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 px-4 border">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="rounded border-input bg-input text-primary focus:ring-primary" id="policyagreement" name="terms" required />
                        <label class="text-sm text-muted-foreground" for="policyagreement"> 
                            I agree to the <a href="#" class="font-bold text-foreground hover:underline">privacy policy & terms</a>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-primary px-4 py-3 text-sm font-bold text-primary-foreground shadow hover:bg-primary/90 transition-all">
                            Sign Up
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
                        Sign up with Google
                    </a>
                    <a href="{{ url('auth/facebook/redirect') }}" class="flex w-full items-center justify-center gap-3 rounded-lg border border-input bg-[#1877F2] px-4 py-3 text-sm font-bold text-white shadow-sm hover:opacity-90 transition-all">
                        <span class="icon-[logos--facebook] size-5 text-white"></span>
                        Sign up with Facebook
                    </a>
                </div>

                <p class="text-center text-sm text-muted-foreground">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-foreground hover:underline">Sign in</a>
                </p>
            </div>
        </div>
    </div>
@endsection
