@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('organizer.sms.index') }}" class="btn btn-ghost btn-circle btn-sm">
                <span class="icon-[tabler--arrow-left] size-5"></span>
            </a>
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Send SMS</h2>
                <p class="text-muted-foreground mt-1">Campaign for: <span class="text-foreground font-semibold">{{ $event->title }}</span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Campaign Form -->
        <div class="md:col-span-2">
            <div class="rounded-xl border border-border bg-card shadow-sm">
                <div class="p-6">
                    <form action="{{ route('organizer.sms.store', $event) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-foreground">Message Content</label>
                                <span id="char-count" class="text-xs text-muted-foreground font-mono">0 / 160</span>
                            </div>
                            
                            <textarea 
                                name="message" 
                                id="sms-message"
                                rows="5" 
                                maxlength="160"
                                class="flex w-full rounded-lg border border-input bg-background px-4 py-3 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all"
                                placeholder="Hi attendee! Your event starts soon. See you there!"
                                required
                            >{{ old('message') }}</textarea>
                            
                            <div class="rounded-lg bg-muted/30 p-4 border border-border">
                                <div class="flex gap-2">
                                    <span class="icon-[tabler--info-circle] size-5 text-primary shrink-0"></span>
                                    <div class="text-xs text-muted-foreground leading-relaxed">
                                        Messages are limited to 160 characters (1 SMS segment). 
                                        Only attendees with a valid phone number will receive the message.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button 
                                type="submit" 
                                class="btn btn-primary w-full gap-2 h-12"
                                @if(!$subscription || ($subscription->sms_used + $attendeeCount > $subscription->package->sms_limit)) disabled @endif
                            >
                                <span class="icon-[tabler--send] size-5"></span>
                                Send to {{ $attendeeCount }} Attendees
                            </button>
                            
                            @if(!$subscription)
                                <p class="text-center text-xs text-destructive mt-3 italic font-medium">Subscription required to send SMS</p>
                            @elseif($subscription->sms_used + $attendeeCount > $subscription->package->sms_limit)
                                <p class="text-center text-xs text-destructive mt-3 italic font-medium">Insufficient SMS credits available</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Campaign Info -->
        <div class="space-y-6">
            <div class="rounded-xl border border-border bg-card p-6 shadow-sm space-y-4">
                <h4 class="font-bold border-b border-border pb-2">Campaign Summary</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Recipients:</span>
                        <span class="font-bold text-foreground">{{ $attendeeCount }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Estimated Credits:</span>
                        <span class="font-bold text-foreground">{{ $attendeeCount }}</span>
                    </div>
                    <div class="my-3 border-t border-border"></div>
                    <div class="flex justify-between text-sm">
                        <span class="text-muted-foreground">Available Credits:</span>
                        <span @class(['font-bold', 'text-destructive' => ($subscription->sms_used + $attendeeCount > $subscription->package->sms_limit)])>
                            {{ $subscription ? $subscription->package->sms_limit - $subscription->sms_used : 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-border bg-primary/5 p-6 shadow-sm">
                <h4 class="font-bold text-primary flex items-center gap-2 mb-3">
                    <span class="icon-[tabler--bulb] size-5"></span>
                    Best Practices
                </h4>
                <ul class="text-xs space-y-2 text-muted-foreground leading-relaxed">
                    <li>• Keep it short and urgent</li>
                    <li>• Include the event name</li>
                    <li>• Don't send too many messages</li>
                    <li>• Avoid special characters to stay within 160 limit</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('sms-message');
        const charCount = document.getElementById('char-count');

        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length} / 160`;
            
            if (length >= 140) {
                charCount.classList.add('text-warning');
            } else {
                charCount.classList.remove('text-warning');
            }
        });
    });
</script>
@endsection
