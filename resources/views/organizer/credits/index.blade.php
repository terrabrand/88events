@extends('layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-foreground">My Wallet</h2>
            <p class="text-muted-foreground mt-1 text-lg">Manage your credits for event promotions.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-lg border-success/20 bg-success/10 text-success-content backdrop-blur-xl">
            <span class="icon-[tabler--check] size-5"></span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Balance Card -->
        <div class="card bg-gradient-to-br from-primary via-primary to-violet-600 text-primary-foreground shadow-2xl shadow-primary/20 relative overflow-hidden h-full border-0">
            <!-- Decorative elements -->
            <div class="absolute -right-16 -top-16 bg-white/20 rounded-full size-64 blur-3xl mix-blend-overlay"></div>
            <div class="absolute -left-10 -bottom-10 bg-black/20 rounded-full size-40 blur-2xl mix-blend-overlay"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
            
            <div class="card-body p-8 relative z-10 flex flex-col justify-between h-full">
                <div class="flex justify-between items-start">
                    <div class="size-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/10 shadow-inner">
                        <span class="icon-[tabler--wallet] size-6 text-white"></span>
                    </div>
                    <span class="badge badge-soft bg-white/20 text-white border-white/10 backdrop-blur-md shadow-sm">Primary Wallet</span>
                </div>
                
                <div class="mt-10">
                    <div class="text-sm font-bold opacity-80 uppercase tracking-widest text-white/90">Available Balance</div>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-2xl font-medium opacity-80">$</span>
                        <span class="text-6xl font-black tracking-tighter text-white drop-shadow-sm">{{ number_format(auth()->user()->credits, 2) }}</span>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between text-sm font-medium text-white/80">
                    <span>Account ID</span>
                    <span class="font-mono opacity-100 tracking-wider">#{{ auth()->id() }}</span>
                </div>
            </div>
        </div>

        <!-- Deposit Card -->
        <div x-data="{ amount: '', gateway: 'manual' }" class="card bg-card border border-border/50 lg:col-span-2 shadow-xl shadow-black/5 hover:border-primary/20 transition-colors">
            <div class="card-header border-b border-border/50 p-6">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="icon-[tabler--circle-plus] size-6"></span>
                    </div>
                    <div>
                        <h3 class="card-title text-xl font-bold">Add Funds</h3>
                        <p class="text-sm text-muted-foreground font-medium">Instant top-up via secure payment.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-8 space-y-8">
                <form action="{{ route('organizer.credits.deposit') }}" method="POST" x-data="{ 
                    gateway: 'manual' 
                }">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-wide mb-3 block">Enter Amount</label>
                            <div class="relative group">
                                <span class="icon-[tabler--currency-dollar] absolute left-5 top-1/2 -translate-y-1/2 size-8 text-muted-foreground group-focus-within:text-primary transition-colors"></span>
                                <input 
                                    type="number" 
                                    name="amount" 
                                    x-model="amount" 
                                    class="input w-full h-20 pl-16 text-5xl font-black tracking-tight bg-muted/30 focus:bg-card border-2 border-transparent focus:border-primary/20 transition-all rounded-2xl placeholder:text-muted-foreground/20 text-foreground" 
                                    placeholder="0.00" 
                                    min="1" 
                                    step="0.01" 
                                    required
                                >
                            </div>
                        </div>

                        <!-- Quick Amounts -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-wide mb-3 block">Quick Select</label>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                                @foreach([10, 25, 50, 100, 500] as $value)
                                    <button 
                                        type="button" 
                                        @click="amount = '{{ $value }}'"
                                        class="btn h-12 text-lg font-bold border-2 transition-all hover:-translate-y-0.5"
                                        :class="amount == '{{ $value }}' ? 'btn-primary border-primary shadow-lg shadow-primary/25' : 'btn-outline border-border hover:border-primary/50 hover:bg-primary/5 hover:text-primary'"
                                    >
                                        ${{ $value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Gateway Selection -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-wide mb-3 block">Select Payment Method</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Manual / Crypto -->
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="gateway" value="manual" x-model="gateway" class="peer sr-only">
                                    <div class="h-full p-4 rounded-xl border-2 border-border/50 bg-card hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5 transition-all flex flex-col items-center gap-2 text-center group">
                                        <div class="size-10 rounded-full bg-muted group-hover:bg-primary/10 peer-checked:bg-primary/20 flex items-center justify-center transition-colors">
                                            <span class="icon-[tabler--building-bank] size-5 text-muted-foreground group-hover:text-primary peer-checked:text-primary transition-colors"></span>
                                        </div>
                                        <span class="font-bold text-sm">Bank / Crypto</span>
                                    </div>
                                    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <span class="icon-[tabler--circle-check-filled] size-5 text-primary"></span>
                                    </div>
                                </label>

                                <!-- Stripe (Mock) -->
                                <label class="cursor-pointer relative opacity-60">
                                    <input type="radio" name="gateway" value="stripe" class="peer sr-only" disabled>
                                    <div class="h-full p-4 rounded-xl border-2 border-border/50 bg-card hover:border-primary/50 transition-all flex flex-col items-center gap-2 text-center">
                                        <div class="size-10 rounded-full bg-muted flex items-center justify-center">
                                            <span class="icon-[tabler--brand-stripe] size-5 text-muted-foreground"></span>
                                        </div>
                                        <span class="font-bold text-sm">Stripe (Soon)</span>
                                    </div>
                                </label>

                                <!-- PayPal (Mock) -->
                                <label class="cursor-pointer relative opacity-60">
                                    <input type="radio" name="gateway" value="paypal" class="peer sr-only" disabled>
                                    <div class="h-full p-4 rounded-xl border-2 border-border/50 bg-card hover:border-primary/50 transition-all flex flex-col items-center gap-2 text-center">
                                        <div class="size-10 rounded-full bg-muted flex items-center justify-center">
                                            <span class="icon-[tabler--brand-paypal] size-5 text-muted-foreground"></span>
                                        </div>
                                        <span class="font-bold text-sm">PayPal (Soon)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Manual Instructions Preview -->
                        <div x-show="gateway === 'manual'" x-transition class="rounded-xl bg-blue-500/10 border border-blue-500/20 p-4 text-sm mt-4">
                            <h4 class="font-bold text-blue-500 flex items-center gap-2 mb-2">
                                <span class="icon-[tabler--info-circle] size-4"></span>
                                Instructions
                            </h4>
                            <p class="text-muted-foreground">
                                For manual payments (Bank Transfer to <b>{{ \App\Models\Setting::get('bank_account_details', 'Not Configured') }}</b> or Crypto), please proceed. You will be redirected to create a support ticket to attach your proof of payment.
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-border/50 flex items-center justify-between">
                        <div class="text-sm text-muted-foreground">
                            <span class="icon-[tabler--lock] inline-block -mt-0.5 size-4"></span>
                            Secure Processing
                        </div>
                        <button type="submit" class="rounded-full border border-input bg-background px-8 py-2 text-sm font-bold text-foreground shadow-sm hover:bg-accent hover:text-accent-foreground transition-all">
                            <span>Proceed</span>
                            <span class="icon-[tabler--arrow-right] size-5"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card bg-card border border-border/50 shadow-lg shadow-black/5 overflow-hidden">
        <div class="card-header border-b border-border/50 bg-muted/20 px-8 py-5 flex items-center justify-between">
            <h3 class="font-black text-lg flex items-center gap-2 tracking-tight">
                <span class="icon-[tabler--history] size-5 text-muted-foreground"></span>
                Transaction History
            </h3>
            <div class="badge badge-outline font-mono text-xs font-bold opacity-60">
                Latest 10
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-muted/30 border-b border-border text-xs uppercase text-muted-foreground font-extrabold tracking-wider">
                        <th class="px-8 py-5 w-[25%] first:pl-8">Date</th>
                        <th class="px-8 py-5 w-[15%]">Type</th>
                        <th class="px-8 py-5 w-[40%]">Description</th>
                        <th class="px-8 py-5 w-[20%] text-right last:pr-8">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($transactions as $txn)
                        <tr class="hover:bg-muted/5 transition-colors group">
                            <td class="px-8 py-5 text-sm text-muted-foreground font-medium group-hover:text-foreground transition-colors">
                                {{ $txn->created_at->format('M d, Y') }}
                                <span class="block text-xs text-muted-foreground/60 font-normal mt-0.5">{{ $txn->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-8 py-5">
                                @if($txn->amount > 0)
                                    <div class="badge badge-soft badge-success gap-1.5 pl-1.5 pr-2.5 shadow-sm shadow-success/10 font-bold border border-success/10">
                                        <div class="size-5 rounded-full bg-success text-success-foreground flex items-center justify-center shadow-inner">
                                            <span class="icon-[tabler--arrow-down-left] size-3.5"></span>
                                        </div>
                                        Deposit
                                    </div>
                                @else
                                    <div class="badge badge-soft badge-neutral gap-1.5 pl-1.5 pr-2.5 font-bold border border-border/50">
                                        <div class="size-5 rounded-full bg-neutral text-neutral-content flex items-center justify-center">
                                            <span class="icon-[tabler--arrow-up-right] size-3.5"></span>
                                        </div>
                                        Spend
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-sm font-medium">{{ $txn->description }}</td>
                            <td class="px-8 py-5 text-right">
                                <span class="font-mono font-bold text-lg {{ $txn->amount > 0 ? 'text-success' : 'text-foreground' }} tracking-tight">
                                    {{ $txn->amount > 0 ? '+' : '' }}{{ number_format($txn->amount, 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-24">
                                <div class="flex flex-col items-center justify-center text-muted-foreground space-y-4">
                                    <div class="size-20 rounded-3xl bg-muted/50 flex items-center justify-center">
                                        <span class="icon-[tabler--receipt-off] size-10 opacity-40"></span>
                                    </div>
                                    <div class="text-center">
                                        <p class="font-bold text-foreground text-lg">No transactions found</p>
                                        <p class="text-sm">Your transaction history will appear here.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border bg-muted/5">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
