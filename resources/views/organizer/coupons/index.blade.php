@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex justify-between items-center">
             <h2 class="text-3xl font-bold tracking-tight text-foreground">My Coupons</h2>
             <span class="text-sm text-muted-foreground">Coupons are managed within each event's edit page</span>
        </div>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Code</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Event</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Type</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Value</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Usage</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Expires</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                                <td class="p-4 align-middle font-mono font-bold">{{ $coupon->code }}</td>
                                <td class="p-4 align-middle">
                                    <a href="{{ route('events.show', $coupon->event) }}" class="hover:underline">{{ $coupon->event->title }}</a>
                                </td>
                                <td class="p-4 align-middle capitalize">{{ $coupon->type }}</td>
                                <td class="p-4 align-middle">{{ $coupon->type == 'fixed' ? '$'.$coupon->amount : $coupon->amount.'%' }}</td>
                                <td class="p-4 align-middle">
                                    {{ $coupon->used_count }} 
                                    @if($coupon->usage_limit)
                                        <span class="text-xs text-muted-foreground">/ {{ $coupon->usage_limit }}</span>
                                    @endif
                                </td>
                                <td class="p-4 align-middle">{{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'Never' }}</td>
                                <td class="p-4 align-middle">
                                    <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete this coupon?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-square btn-ghost btn-sm text-destructive hover:text-destructive/80">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-center text-muted-foreground opacity-50">No coupons found. Create coupons from your Event pages.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $coupons->links() }}
        </div>
    </div>
@endsection

