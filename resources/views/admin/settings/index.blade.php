@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <h2 class="text-3xl font-bold tracking-tight text-foreground">App Settings</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="icon-[lucide--credit-card] size-6 text-primary"></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Payment Gateways</p>
                        <h3 class="text-2xl font-bold">
                            @php
                                $activeGateways = collect(['stripe', 'paypal', 'paystack', 'bank_transfer', 'cash_on_hand'])
                                    ->filter(fn($g) => \App\Models\Setting::get($g . '_enabled') == '1')
                                    ->count();
                            @endphp
                            {{ $activeGateways }} Active
                        </h3>
                    </div>
                </div>
                <a href="{{ route('admin.settings.payment') }}" class="btn btn-sm btn-ghost w-full">Manage Gateways</a>
            </div>

            <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="icon-[lucide--settings] size-6 text-primary"></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">General Configuration</p>
                        <h3 class="text-2xl font-bold">App Settings</h3>
                    </div>
                </div>
                <button class="btn btn-sm btn-ghost w-full" disabled>Configure System</button>
            </div>
        </div>

        <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
            <div class="p-6">
                <form class="flex flex-col gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">App Name</label>
                        <input type="text" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="{{ config('app.name') }}">
                    </div>
                    
                    <div class="space-y-2">
                         <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Support Email</label>
                        <input type="email" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" value="support@example.com">
                    </div>
                    
                    <div class="flex items-center space-x-2">
                         <input type="checkbox" class="h-4 w-4 shrink-0 rounded-sm border border-primary ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground" id="maintenance_mode" />
                         <label for="maintenance_mode" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Maintenance Mode</label>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
