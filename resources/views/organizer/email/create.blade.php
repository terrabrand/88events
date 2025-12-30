@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('organizer.email.index') }}" class="btn btn-ghost btn-circle btn-sm">
                <span class="icon-[tabler--arrow-left] size-5"></span>
            </a>
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Compose Campaign</h2>
                <p class="text-muted-foreground mt-1">Sending to attendees of <span class="text-foreground font-semibold">{{ $event->title }}</span></p>
            </div>
        </div>
    </div>

    <form action="{{ route('organizer.email.store', $event) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <div class="lg:col-span-2 space-y-6">
            <!-- Email Editor -->
            <div class="rounded-xl border border-border bg-card shadow-sm p-6 space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-semibold">Email Subject</label>
                    <input 
                        type="text" 
                        name="subject" 
                        class="input input-bordered w-full" 
                        placeholder="Exciting news about {{ $event->title }}!"
                        required
                    >
                    @error('subject') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold">Email Content (HTML supported)</label>
                    <textarea 
                        name="content" 
                        id="email-editor"
                        rows="12" 
                        class="textarea textarea-bordered w-full font-mono text-sm"
                        placeholder="<h1>Hello!</h1> <p>We wanted to remind you that...</p>"
                        required
                    ></textarea>
                     @error('content') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Campaign Settings -->
            <div class="rounded-xl border border-border bg-card p-6 shadow-sm space-y-6">
                <h4 class="font-bold border-b border-border pb-2">Campaign settings</h4>
                
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Target Audience:</span>
                        <span class="font-bold">{{ $attendeeCount }} People</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Current Plan:</span>
                        <span class="font-bold">{{ $subscription ? $subscription->package->name : 'None' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Available Credits:</span>
                        <span @class(['font-bold', 'text-destructive' => ($subscription && $subscription->email_used + $attendeeCount > $subscription->package->email_limit)])>
                            {{ $subscription ? $subscription->package->email_limit - $subscription->email_used : 0 }}
                        </span>
                    </div>
                </div>

                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="btn btn-primary w-full gap-2 h-12"
                        @if(!$subscription || ($subscription->email_used + $attendeeCount > $subscription->package->email_limit)) disabled @endif
                    >
                        <span class="icon-[tabler--mail-fast] size-5"></span>
                        Send via Mailchimp
                    </button>
                    @if(!$subscription)
                        <p class="text-center text-xs text-destructive mt-3 italic font-medium">Active subscription required</p>
                    @elseif($subscription->email_used + $attendeeCount > $subscription->package->email_limit)
                        <p class="text-center text-xs text-destructive mt-3 italic font-medium">Insufficient email credits</p>
                    @endif
                </div>
            </div>

            <!-- Tips Card -->
            <div class="rounded-xl border border-border bg-primary/5 p-6 shadow-sm">
                <h4 class="font-bold text-primary flex items-center gap-2 mb-3">
                    <span class="icon-[tabler--bulb] size-5"></span>
                    Email Tips
                </h4>
                <ul class="text-xs space-y-2 text-muted-foreground leading-relaxed">
                    <li>• Use a compelling subject line</li>
                    <li>• Personalize your greeting</li>
                    <li>• Keep HTML simple for better deliverability</li>
                    <li>• Include a clear Call to Action (CTA)</li>
                </ul>
            </div>
        </div>
    </form>
</div>
@endsection
