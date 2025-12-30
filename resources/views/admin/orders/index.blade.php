@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <h2 class="text-3xl font-bold tracking-tight text-foreground">Orders Management</h2>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Ref</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Event</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Ticket Type</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Amount</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-b border-border hover:bg-muted/50 transition-colors">
                                <td class="p-4 align-middle font-mono text-xs">{{ $order->transaction_ref }}</td>
                                <td class="p-4 align-middle">
                                    <div class="font-medium">{{ $order->user->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $order->user->email }}</div>
                                </td>
                                <td class="p-4 align-middle">{{ $order->event->title ?? 'N/A' }}</td>
                                <td class="p-4 align-middle">{{ $order->ticketType->name ?? 'N/A' }}</td>
                                <td class="p-4 align-middle font-medium">{{ $order->currency }} {{ number_format($order->amount, 2) }}</td>
                                <td class="p-4 align-middle">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                        {{ $order->status === 'completed' ? 'border-transparent bg-green-500/15 text-green-700' : 'border-transparent bg-yellow-500/15 text-yellow-700' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle text-xs">{{ $order->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-center text-muted-foreground opacity-50">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection

