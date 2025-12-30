@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Payment Gateways</h2>
                <p class="text-sm text-muted-foreground">Configure and manage payment methods available across the platform.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-success/50 bg-success/10 p-4 text-success flex items-center gap-2">
                <span class="icon-[lucide--check-circle] size-5"></span>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.payment.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Stripe -->
            <div class="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-primary/20 hover:shadow-md">
                <div class="flex items-center justify-between p-6 border-b border-border bg-muted/30">
                    <div class="flex items-center gap-4">
                        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="icon-[lucide--credit-card] size-6 text-primary"></span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Stripe</h3>
                            <div class="flex items-center gap-2">
                                @if(\App\Models\Setting::get('stripe_enabled') == '1')
                                    <span class="badge badge-success badge-soft badge-xs font-bold uppercase">Active</span>
                                @else
                                    <span class="badge badge-error badge-soft badge-xs font-bold uppercase">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="stripe_enabled" value="1" {{ \App\Models\Setting::get('stripe_enabled') == '1' ? 'checked' : '' }} class="toggle toggle-primary">
                    </label>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Public Key</label>
                        <input type="text" name="stripe_public_key" value="{{ \App\Models\Setting::get('stripe_public_key') }}" class="input input-bordered w-full font-mono text-sm" placeholder="pk_test_...">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Secret Key</label>
                        <input type="password" name="stripe_secret_key" value="{{ \App\Models\Setting::get('stripe_secret_key') }}" class="input input-bordered w-full font-mono text-sm" placeholder="sk_test_...">
                    </div>
                </div>
            </div>

            <!-- PayPal -->
            <div class="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-primary/20 hover:shadow-md">
                <div class="flex items-center justify-between p-6 border-b border-border bg-muted/30">
                    <div class="flex items-center gap-4">
                        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="icon-[lucide--box] size-6 text-primary"></span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">PayPal</h3>
                            <div class="flex items-center gap-2">
                                @if(\App\Models\Setting::get('paypal_enabled') == '1')
                                    <span class="badge badge-success badge-soft badge-xs font-bold uppercase">Active</span>
                                @else
                                    <span class="badge badge-error badge-soft badge-xs font-bold uppercase">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="paypal_enabled" value="1" {{ \App\Models\Setting::get('paypal_enabled') == '1' ? 'checked' : '' }} class="toggle toggle-primary">
                    </label>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Client ID</label>
                        <input type="text" name="paypal_client_id" value="{{ \App\Models\Setting::get('paypal_client_id') }}" class="input input-bordered w-full font-mono text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Secret</label>
                        <input type="password" name="paypal_secret" value="{{ \App\Models\Setting::get('paypal_secret') }}" class="input input-bordered w-full font-mono text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Environment</label>
                        <select name="paypal_mode" class="select select-bordered w-full">
                            <option value="sandbox" {{ \App\Models\Setting::get('paypal_mode') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                            <option value="live" {{ \App\Models\Setting::get('paypal_mode') == 'live' ? 'selected' : '' }}>Live</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Paystack -->
            <div class="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-primary/20 hover:shadow-md">
                <div class="flex items-center justify-between p-6 border-b border-border bg-muted/30">
                    <div class="flex items-center gap-4">
                        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="icon-[lucide--zap] size-6 text-primary"></span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Paystack</h3>
                            <div class="flex items-center gap-2">
                                @if(\App\Models\Setting::get('paystack_enabled') == '1')
                                    <span class="badge badge-success badge-soft badge-xs font-bold uppercase">Active</span>
                                @else
                                    <span class="badge badge-error badge-soft badge-xs font-bold uppercase">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="paystack_enabled" value="1" {{ \App\Models\Setting::get('paystack_enabled') == '1' ? 'checked' : '' }} class="toggle toggle-primary">
                    </label>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Public Key</label>
                        <input type="text" name="paystack_public_key" value="{{ \App\Models\Setting::get('paystack_public_key') }}" class="input input-bordered w-full font-mono text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Secret Key</label>
                        <input type="password" name="paystack_secret_key" value="{{ \App\Models\Setting::get('paystack_secret_key') }}" class="input input-bordered w-full font-mono text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase text-muted-foreground">Merchant Email</label>
                        <input type="email" name="paystack_merchant_email" value="{{ \App\Models\Setting::get('paystack_merchant_email') }}" class="input input-bordered w-full">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bank Transfer -->
                <div class="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-primary/20 hover:shadow-md">
                    <div class="flex items-center justify-between p-6 border-b border-border bg-muted/30">
                        <div class="flex items-center gap-4">
                            <div class="size-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                                <span class="icon-[lucide--landmark] size-6 text-secondary"></span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Bank Transfer</h3>
                                <div class="flex items-center gap-2">
                                    @if(\App\Models\Setting::get('bank_transfer_enabled') == '1')
                                        <span class="badge badge-success badge-soft badge-xs font-bold uppercase">Active</span>
                                    @else
                                        <span class="badge badge-error badge-soft badge-xs font-bold uppercase">Disabled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input type="checkbox" name="bank_transfer_enabled" value="1" {{ \App\Models\Setting::get('bank_transfer_enabled') == '1' ? 'checked' : '' }} class="toggle toggle-secondary">
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase text-muted-foreground">Transfer Instructions</label>
                            <textarea name="bank_account_details" class="textarea textarea-bordered w-full h-32 text-sm" placeholder="Bank Name: ...&#10;Account Number: ...">{{ \App\Models\Setting::get('bank_account_details') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Cash on Hand -->
                <div class="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-primary/20 hover:shadow-md">
                    <div class="flex items-center justify-between p-6 border-b border-border bg-muted/30">
                        <div class="flex items-center gap-4">
                            <div class="size-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                                <span class="icon-[lucide--wallet] size-6 text-secondary"></span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Cash on Hand</h3>
                                <div class="flex items-center gap-2">
                                    @if(\App\Models\Setting::get('cash_on_hand_enabled') == '1')
                                        <span class="badge badge-success badge-soft badge-xs font-bold uppercase">Active</span>
                                    @else
                                        <span class="badge badge-error badge-soft badge-xs font-bold uppercase">Disabled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input type="checkbox" name="cash_on_hand_enabled" value="1" {{ \App\Models\Setting::get('cash_on_hand_enabled') == '1' ? 'checked' : '' }} class="toggle toggle-secondary">
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase text-muted-foreground">Collection Instructions</label>
                            <textarea name="cash_instructions" class="textarea textarea-bordered w-full h-32 text-sm">{{ \App\Models\Setting::get('cash_instructions') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end sticky bottom-6 z-20">
                <button type="submit" class="btn btn-primary px-10 shadow-xl shadow-primary/30 font-bold">Update All Gateways</button>
            </div>
        </form>
    </div>
@endsection
