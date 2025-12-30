@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <h2 class="text-3xl font-bold tracking-tight text-foreground">Reported Events</h2>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Event</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Reason</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Details</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr class="border-b border-border hover:bg-muted/50 transition-colors last:border-0">
                                <td class="p-4 align-middle">
                                    <span class="inline-flex items-center rounded-full border border-transparent px-2.5 py-0.5 text-xs font-semibold
                                        {{ $report->status == 'pending' ? 'bg-warning text-warning-foreground' : ($report->status == 'resolved' ? 'bg-success text-success-foreground' : 'bg-secondary text-secondary-foreground') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle font-medium">{{ $report->user->name }}</td>
                                <td class="p-4 align-middle">
                                    <a href="{{ route('events.show', $report->event) }}" target="_blank" class="text-primary hover:underline">
                                        {{ $report->event->title }}
                                    </a>
                                </td>
                                <td class="p-4 align-middle font-medium">{{ $report->reason }}</td>
                                <td class="p-4 align-middle max-w-xs truncate text-muted-foreground">{{ $report->details }}</td>
                                <td class="p-4 align-middle">
                                    @if($report->status == 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="resolved">
                                                <button class="inline-flex items-center rounded-md border border-transparent bg-success text-success-foreground shadow hover:bg-success/80 h-7 px-3 text-xs font-semibold">Resolve</button>
                                            </form>
                                            <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="dismissed">
                                                <button class="inline-flex items-center rounded-md border border-input bg-background shadow-sm hover:bg-muted hover:text-muted-foreground h-7 px-3 text-xs font-semibold">Dismiss</button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-muted-foreground opacity-60">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div>{{ $reports->links() }}</div>
    </div>
@endsection
