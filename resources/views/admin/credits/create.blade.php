@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-bold tracking-tight text-foreground">Manual Credit Deposit</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-success/20">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <div class="card bg-card border border-border shadow-sm">
        <div class="card-body p-8">
            <form action="{{ route('admin.credits.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-sm font-bold">Select Organizer</label>
                    <select name="user_id" class="select select-bordered w-full" required>
                        <option value="" disabled selected>Choose an organizer...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}) - Cur: {{ $user->credits }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold">Amount to Add</label>
                    <div class="relative">
                        <span class="icon-[tabler--currency-dollar] absolute left-3 top-3.5 size-5 text-muted-foreground"></span>
                        <input type="number" name="amount" class="input input-bordered w-full pl-10" placeholder="0.00" min="0.01" step="0.01" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold">Description / Reason</label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="e.g. Manual Bank Transfer #1234, Compensation, etc." required>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/20 font-bold">
                        <span class="icon-[tabler--plus] mr-2 size-5"></span>
                        Add Credits
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
