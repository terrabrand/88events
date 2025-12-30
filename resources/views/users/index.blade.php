@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Users</h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow">
                <span class="icon-[tabler--plus] size-4 mr-2"></span>
                Add User
            </a>
        </div>

        @if(session('success'))
             <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
                <span class="icon-[tabler--check] size-5"></span>
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Name</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Role</th>
                            @if(auth()->user()->hasRole('admin'))
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Parent Account</th>
                            @endif
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Created At</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b border-border hover:bg-muted/50 transition-colors last:border-0">
                                <td class="p-4 align-middle font-medium">{{ $user->name }}</td>
                                <td class="p-4 align-middle">{{ $user->email }}</td>
                                <td class="p-4 align-middle">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-secondary text-secondary-foreground capitalize">
                                        {{ $user->getRoleNames()->first() ?? 'N/A' }}
                                    </span>
                                </td>
                                @if(auth()->user()->hasRole('admin'))
                                    <td class="p-4 align-middle">
                                        @if($user->parent)
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ $user->parent->name }}</span>
                                                <span class="text-xs text-muted-foreground">{{ $user->parent->email }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted-foreground/50">-</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="p-4 align-middle text-muted-foreground">{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="p-4 align-middle flex gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-ghost btn-square" aria-label="Edit user">
                                        <span class="icon-[tabler--pencil] size-4"></span>
                                    </a>
                                    
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-ghost btn-square text-destructive hover:bg-destructive/10" aria-label="Delete">
                                                <span class="icon-[tabler--trash] size-4"></span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('admin') ? 6 : 5 }}" class="text-center p-8 text-muted-foreground">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

