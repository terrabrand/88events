@extends('layouts.public')

@section('public-content')
    <article class="container mx-auto max-w-4xl py-12 px-4 sm:px-6">
        <div class="mb-8">
            <a href="{{ route('blog.index') }}" class="mb-4 inline-flex items-center text-sm text-muted-foreground hover:text-foreground">
                <span class="icon-[tabler--arrow-left] mr-1 size-4"></span>
                Back to Blog
            </a>
            <h1 class="text-4xl font-bold tracking-tight text-foreground sm:text-5xl lg:text-6xl">{{ $post->title }}</h1>
            <div class="mt-6 flex items-center space-x-4 text-sm text-muted-foreground">
                <div class="flex items-center">
                    <span class="icon-[tabler--calendar] mr-1 size-4"></span>
                    {{ $post->published_at->format('F d, Y') }}
                </div>
                <div>&bull;</div>
                <div class="flex items-center">
                    <span class="icon-[tabler--user] mr-1 size-4"></span>
                    {{ $post->author->name }}
                </div>
            </div>
        </div>

        @if($post->featured_image)
            <div class="mb-12 overflow-hidden rounded-xl border border-border shadow-sm">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full object-cover">
            </div>
        @endif

        <div class="prose prose-lg dark:prose-invert max-w-none">
            {!! nl2br(e($post->content)) !!}
        </div>
        
        <div class="mt-12 border-t border-border pt-8">
            <div class="flex justify-between items-center">
                 <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    <span class="icon-[tabler--arrow-left] mr-2 size-4"></span>
                    Back to All Posts
                </a>
            </div>
        </div>
    </article>
@endsection
