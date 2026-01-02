<?php

use App\Models\Promotion;
use App\Models\Event;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- PROMOTIONS ---\n";
$promotions = Promotion::with('event')->get();
foreach ($promotions as $p) {
    echo "ID: {$p->id} | EventID: {$p->event_id} | Title: " . ($p->event->title ?? 'NULL') . " | Status: {$p->status}\n";
}

echo "\n--- EVENTS ---\n";
$events = Event::all();
foreach ($events as $e) {
    echo "ID: {$e->id} | Title: {$e->title} | OrganizerID: {$e->organizer_id}\n";
}
