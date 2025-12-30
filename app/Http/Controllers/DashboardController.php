<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('organizer')) {
            return $this->organizerDashboard($user);
        }

        if ($user->hasRole('scanner')) {
            return $this->scannerDashboard($user);
        }

        return $this->attendeeDashboard($user);
    }

    private function scannerDashboard($user)
    {
        $assignedEvents = $user->assignedEvents()
            ->where('end_date', '>=', now()) // Show events that haven't ended
            ->orderBy('start_date')
            ->get();

        return view('dashboard', [
            'mode' => 'scanner',
            'assignedEvents' => $assignedEvents,
            'title' => 'Scanner Dashboard'
        ]);
    }

    private function organizerDashboard($user)
    {
        $eventsQuery = Event::query();
        if (!$user->hasRole('admin')) {
            $eventsQuery->where('organizer_id', $user->id);
        }

        // Stats
        $totalEvents = $eventsQuery->count();
        
        // Income & Sales (Need to join events if not admin)
        $transactionsQuery = Transaction::where('status', 'completed');
        if (!$user->hasRole('admin')) {
            $transactionsQuery->whereHas('event', function($q) use ($user) {
                $q->where('organizer_id', $user->id);
            });
        }
        
        $totalRevenue = $transactionsQuery->sum('amount');
        $ticketsSold = $transactionsQuery->get()->sum(function($txn) {
            return $txn->meta_data['quantity'] ?? 0;
        });

        // Top Selling Events
        $topEvents = $eventsQuery->withCount('tickets')
            ->orderBy('tickets_count', 'desc')
            ->take(5)
            ->get();

        // Recent Sales
        $recentSales = $transactionsQuery->latest()
            ->with(['user', 'event'])
            ->take(10)
            ->get();

        return view('dashboard', [
            'mode' => 'organizer',
            'totalEvents' => $totalEvents,
            'totalRevenue' => $totalRevenue,
            'ticketsSold' => $ticketsSold,
            'topEvents' => $topEvents,
            'recentSales' => $recentSales,
            'title' => 'Organizer Dashboard'
        ]);
    }

    private function attendeeDashboard($user)
    {
        $upcomingTickets = Ticket::where('user_id', $user->id)
            ->whereHas('event', function($q) {
                $q->where('start_date', '>=', now());
            })
            ->with('event')
            ->take(3)
            ->get();

        $pastEventsCount = Ticket::where('user_id', $user->id)
            ->whereHas('event', function($q) {
                $q->where('start_date', '<', now());
            })
            ->count();

        // Recommended Events (Random for now)
        $recommendedEvents = Event::where('start_date', '>', now())
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Promoter Stats
        $promoterStats = null;
        if ($user->hasRole('promoter')) {
            $referrals = Transaction::where('promoter_id', $user->id)
                ->where('status', 'completed')
                ->get();
            
            $promoterStats = [
                'referral_code' => $user->referral_code,
                'total_referrals' => $referrals->count(),
                'total_commission' => $referrals->sum('commission_amount'),
                'recent_referrals' => $referrals->take(5),
            ];
        }

        return view('dashboard', [
            'mode' => 'attendee',
            'upcomingTickets' => $upcomingTickets,
            'pastEventsCount' => $pastEventsCount,
            'recommendedEvents' => $recommendedEvents,
            'promoterStats' => $promoterStats,
            'title' => 'My Dashboard'
        ]);
    }
}
