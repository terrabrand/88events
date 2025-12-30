@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Create New User</h2>
            <a href="{{ route('users.index') }}" class="btn btn-outline btn-sm">
                <span class="icon-[tabler--arrow-left] mr-2 size-4"></span>
                Back
            </a>
        </div>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="name">Name</label>
                        <input type="text" name="name" id="name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('name') }}" required autofocus>
                        @error('name') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none" for="email">Email</label>
                        <input type="email" name="email" id="email" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ old('email') }}" required>
                        @error('email') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                         <label class="text-sm font-medium leading-none" for="role">Role</label>
                         <select name="role" id="role" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required>
                             @if(auth()->user()->hasRole('organizer'))
                                 <option value="scanner" {{ old('role') == 'scanner' ? 'selected' : '' }}>Scanner</option>
                                 <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }} disabled title="Comming soon">Staff (Coming Soon)</option>
                             @elseif(auth()->user()->hasRole('admin'))
                                 <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>Organizer</option>
                                 <option value="scanner" {{ old('role') == 'scanner' ? 'selected' : '' }}>Scanner</option>
                                 <option value="attendee" {{ old('role') == 'attendee' ? 'selected' : '' }}>Attendee</option>
                                 <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                             @endif
                         </select>
                         @error('role') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="password">Password</label>
                            <input type="password" name="password" id="password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required autocomplete="new-password">
                            @error('password') <span class="text-sm font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none" for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
