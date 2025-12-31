<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdPackage;
use App\Models\Event;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = auth()->user()->promotions()->with(['event', 'package'])->latest()->paginate(10);
        return view('organizer.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $events = auth()->user()->events()->where('status', 'published')->get();
        $packages = AdPackage::active()->get();
        return view('organizer.promotions.create', compact('events', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'ad_package_id' => 'required|exists:ad_packages,id',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $event = auth()->user()->events()->findOrFail($validated['event_id']);
        $package = AdPackage::active()->findOrFail($validated['ad_package_id']);
        $cost = $package->price;
        $user = auth()->user();

        if ($user->credits < $cost) {
            return redirect()->back()->withErrors(['error' => 'Insufficient credits. Please top up your wallet.']);
        }

        try {
            DB::transaction(function () use ($user, $event, $package, $validated, $cost) {
                // Deduct credits
                $user->chargeCredits($cost, "Promotion for event: {$event->title} ({$package->name})");

                // Create Promotion
                Promotion::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'ad_package_id' => $package->id,
                    'start_date' => $validated['start_date'],
                    'end_date' => \Carbon\Carbon::parse($validated['start_date'])->addDays($package->duration_days),
                    'status' => 'pending', // Or active depending on settings
                    'cost' => $cost,
                    'payment_method' => 'credit',
                ]);
            });

            return redirect()->route('organizer.promotions.index')->with('success', 'Promotion created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

}
