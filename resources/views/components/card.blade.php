@props(['title' => null, 'description' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-border bg-card text-card-foreground shadow-sm']) }}>
    @if($title || $description)
        <div class="flex flex-col space-y-1.5 p-6">
            @if($title)
                <h3 class="text-2xl font-semibold leading-none tracking-tight">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="text-sm text-muted-foreground">{{ $description }}</p>
            @endif
        </div>
    @endif
    
    <div class="p-6 {{ $title || $description ? 'pt-0' : '' }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="flex items-center p-6 pt-0">
            {{ $footer }}
        </div>
    @endif
</div>
