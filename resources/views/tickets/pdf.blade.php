<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket - {{ $ticket->ticket_code }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .ticket { border: 2px solid #000; padding: 20px; max-width: 800px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px dashed #ccc; padding-bottom: 20px; margin-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; margin: 0; }
        .meta { margin-top: 10px; font-size: 14px; }
        .content { display: table; width: 100%; }
        .details { display: table-cell; vertical-align: top; width: 60%; }
        .qr { display: table-cell; vertical-align: top; width: 40%; text-align: right; }
        .label { font-size: 10px; color: #666; text-transform: uppercase; margin-top: 10px; }
        .value { font-size: 16px; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1 class="title">{{ $ticket->event->title }}</h1>
            <div class="meta">
                {{ $ticket->event->start_date->format('l, F j, Y \a\t h:i A') }} <br>
                {{ $ticket->event->location_type == 'virtual' ? 'Online Event' : $ticket->event->venue_address }}
            </div>
        </div>

        <div class="content">
            <div class="details">
                <div class="label">Attendee</div>
                <div class="value">{{ $ticket->user->name }}</div>

                <div class="label">Ticket Type</div>
                <div class="value">{{ $ticket->ticketType->name }}</div>

                <div class="label">Price</div>
                <div class="value">
                    {{ $ticket->ticketType->price > 0 ? '$' . number_format($ticket->ticketType->price, 2) : 'Free' }}
                </div>

                <div class="label">Ticket Code</div>
                <div class="value" style="font-family: monospace; letter-spacing: 2px;">{{ $ticket->ticket_code }}</div>
            </div>
            
            <div class="qr">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="200" height="200" alt="QR Code">
            </div>
        </div>

        <div class="footer">
            Present this QR code at the entrance for verification.
        </div>
    </div>
</body>
</html>
