@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-lg space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-bold tracking-tight text-foreground">Ticket Scanner</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">
            <span class="icon-[tabler--arrow-left] mr-2 size-4"></span>
            Back
        </a>
    </div>

    @if(isset($event) && $event)
        <div class="rounded-lg border border-primary/20 bg-primary/5 p-4 text-center">
             <p class="text-sm text-muted-foreground uppercase font-bold tracking-wider">Active Event</p>
             <h3 class="text-xl font-bold text-primary mt-1">{{ $event->title }}</h3>
             <p class="text-xs text-muted-foreground mt-1">{{ $event->start_date->format('M d, Y') }} &bull; {{ $event->venue_address ?? 'Online' }}</p>
        </div>
    @else
         <div class="rounded-lg border border-warning/50 bg-warning/10 p-4 text-center text-warning-foreground">
             <span class="icon-[tabler--alert-triangle] size-5 mb-1 inline-block"></span>
             <p class="font-medium">No Specific Event Selected</p>
             <p class="text-xs opacity-80">Scanning will validate against all authorized events.</p>
        </div>
    @endif

    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
        <div class="p-6">
            <div id="reader" class="w-full overflow-hidden rounded-lg"></div>
            
            <div id="result" class="mt-6 hidden rounded-lg border p-6 flex flex-col items-center text-center animate-in fade-in zoom-in duration-300">
                <span id="result-icon" class="size-10 mb-3"></span>
                <h3 id="result-title" class="font-bold text-2xl">Title</h3>
                <p id="result-message" class="text-muted-foreground mt-2">Message</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scanner UI Customization */
    #reader {
        border: none !important;
        font-family: inherit !important;
    }
    #reader button {
        background-color: hsl(var(--p)) !important;
        color: hsl(var(--pc)) !important;
        border: none !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.5rem !important;
        font-weight: 500 !important;
        font-size: 0.875rem !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        margin: 0.5rem 0 !important;
    }
    #reader button:hover {
        opacity: 0.9 !important;
    }
    #reader a {
        color: hsl(var(--p)) !important;
        text-decoration: none !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        margin: 0.5rem 0 !important;
        display: inline-block !important;
    }
    #reader a:hover {
        text-decoration: underline !important;
    }
    #reader__header_message {
        font-size: 0.875rem !important;
        color: hsl(var(--muted-foreground)) !important;
        margin-bottom: 1rem !important;
    }
    #reader__status_span {
        font-size: 0.875rem !important;
        color: hsl(var(--foreground)) !important;
    }
    /* Hide the scan region border/background if confusing */
    #reader__scan_region {
        background: transparent !important;
    }
</style>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultDiv = document.getElementById('result');
        const resultIcon = document.getElementById('result-icon');
        const resultTitle = document.getElementById('result-title');
        const resultMessage = document.getElementById('result-message');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Prevent double scanning
        let isScanning = true;

        function onScanSuccess(decodedText, decodedResult) {
            if (!isScanning) return;
            
            isScanning = false;
            // console.log(`Scan result: ${decodedText}`);
            
            // Post to check-in
            fetch("{{ route('scanner.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    ticket_code: decodedText,
                    event_id: '{{ $event?->id }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                html5QrcodeScanner.pause();
                
                resultDiv.classList.remove('hidden', 'bg-success/15', 'border-success', 'text-success', 'bg-destructive/15', 'border-destructive', 'text-destructive');
                
                if(data.valid) {
                     resultDiv.classList.add('bg-success/15', 'border-success', 'text-success');
                     resultIcon.className = 'icon-[tabler--check] size-8 mb-2';
                     resultTitle.textContent = 'ACCESS GRANTED';
                     let seatInfo = data.ticket.seat_number ? `<br><span class="text-xs font-black">SEAT: ${data.ticket.seat_number}</span>` : '';
                     resultMessage.innerHTML = `<span class="font-bold">${data.ticket.type}</span><br>${data.ticket.attendee}${seatInfo}`;
                } else {
                     resultDiv.classList.add('bg-destructive/15', 'border-destructive', 'text-destructive');
                     resultIcon.className = 'icon-[tabler--x] size-8 mb-2';
                     resultTitle.textContent = 'DECLINED';
                     resultMessage.textContent = data.message;
                }
                
                // Restart scanner after 3 seconds
                setTimeout(() => {
                    resultDiv.classList.add('hidden');
                    html5QrcodeScanner.resume();
                    isScanning = true;
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                html5QrcodeScanner.resume();
                isScanning = true;
            });
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endpush
@endsection
