@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <h2 class="text-3xl font-bold tracking-tight text-foreground">Event Reviews</h2>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Event</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Rating</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Comment</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr class="border-b border-border hover:bg-muted/50 transition-colors last:border-0">
                                <td class="p-4 align-middle text-muted-foreground">{{ $review->created_at->format('M d, Y') }}</td>
                                <td class="p-4 align-middle font-medium">{{ $review->user->name }}</td>
                                <td class="p-4 align-middle">{{ $review->event->title }}</td>
                                <td class="p-4 align-middle">
                                    <div class="flex text-orange-400">
                                         @for($i=1; $i<=5; $i++)
                                            <span class="icon-[tabler--star{{ $i <= $review->rating ? '-filled' : '' }}] size-4"></span>
                                        @endfor
                                    </div>
                                </td>
                                <td class="p-4 align-middle max-w-xs truncate text-muted-foreground">{{ $review->comment }}</td>
                                <td class="p-4 align-middle">
                                    <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="inline-flex items-center rounded-md border text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent shadow hover:bg-opacity-80 h-7 px-3 {{ $review->is_approved ? 'bg-secondary text-secondary-foreground' : 'bg-primary text-primary-foreground' }}">
                                            {{ $review->is_approved ? 'Hide' : 'Approve' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-ghost btn-square text-destructive hover:bg-destructive/10">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-muted-foreground opacity-60">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div>{{ $reviews->links() }}</div>
    </div>
@endsection
