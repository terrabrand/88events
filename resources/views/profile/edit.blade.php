@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Profile Settings</h1>
            <p class="text-muted-foreground">Manage your account settings and preferences.</p>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6 space-y-6">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="avatar">Profile Picture</label>
                        <div class="flex items-center gap-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 rounded-full bg-muted flex items-center justify-center text-xl font-bold text-muted-foreground">
                                    {{ $user->initials() }}
                                </div>
                            @endif
                            <input type="file" name="avatar" id="avatar" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" accept="image/*">
                        </div>
                        @error('avatar') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="name">Name</label>
                        <input type="text" name="name" id="name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="email">Email</label>
                        <input type="email" name="email" id="email" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">Save Changes</button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-muted-foreground" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Saved.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
