@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Blog Posts</h2>
            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                <span class="icon-[tabler--plus] mr-2 size-4"></span>
                Create New Post
            </a>
        </div>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground uppercase bg-muted/50">
                            <tr>
                                <th class="px-4 py-3 font-medium">Title</th>
                                <th class="px-4 py-3 font-medium">Author</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Published At</th>
                                <th class="px-4 py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                <tr class="border-b border-border hover:bg-muted/50 transition-colors last:border-0">
                                    <td class="px-4 py-3 font-medium">
                                        {{ $post->title }}
                                        <div class="text-xs text-muted-foreground">{{Str::limit($post->excerpt, 50)}}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $post->author->name }}</td>
                                    <td class="px-4 py-3">
                                        @if($post->is_published)
                                            <span class="inline-flex items-center rounded-full border border-transparent bg-green-500/15 text-green-700 px-2.5 py-0.5 text-xs font-semibold">Published</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full border border-transparent bg-gray-500/15 text-gray-700 px-2.5 py-0.5 text-xs font-semibold">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-transparent text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50">
                                                <span class="icon-[tabler--pencil] size-4"></span>
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-transparent text-sm font-medium shadow-sm transition-colors hover:bg-destructive hover:text-destructive-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50">
                                                    <span class="icon-[tabler--trash] size-4"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-muted-foreground opacity-60">No posts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
