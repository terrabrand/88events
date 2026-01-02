@extends('layouts.public')

@section('public-content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-foreground sm:text-5xl">Our Blog</h1>
            <p class="mt-4 text-lg text-muted-foreground">Latest news, updates, and articles from our team.</p>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($posts as $post)
                <article class="flex flex-col overflow-hidden rounded-lg border border-border bg-card shadow-sm transition-shadow hover:shadow-md">
                    @if($post->featured_image)
                        <div class="aspect-video w-full overflow-hidden">
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    @else
                        <div class="aspect-video w-full bg-muted/50 flex items-center justify-center">
                            <span class="icon-[tabler--photo] size-12 text-muted-foreground/50"></span>
                        </div>
                    @endif
                    
                    <div class="flex flex-1 flex-col p-6">
                        <div class="mb-2 text-sm text-muted-foreground">
                            {{ $post->published_at->format('M d, Y') }} &bull; {{ $post->read_time ?? '5 min' }} read
                        </div>
                        <h3 class="mb-2 text-xl font-bold tracking-tight text-foreground">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                        </h3>
                        <p class="mb-4 flex-1 text-muted-foreground line-clamp-3">
                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 150) }}
                        </p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-sm font-medium text-primary hover:underline">
                            Read more
                            <span class="icon-[tabler--arrow-right] ml-1 size-4"></span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-12 text-center">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                        <span class="icon-[tabler--news] size-6 text-muted-foreground"></span>
                    </div>
                    <h3 class="text-lg font-medium text-foreground">No posts found</h3>
                    <p class="text-muted-foreground">Check back later for new content.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
