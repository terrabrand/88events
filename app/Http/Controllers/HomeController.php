<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\FeaturedItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $featuredEvents = Event::where('status', 'published')
            ->where('is_featured', true)
            ->latest()
            ->take(5)
            ->get();
        
        $latestEvents = Event::where('status', 'published')
            ->with(['venue', 'ticketTypes'])
            ->withPromotionStatus() // Adds is_promoted column (0 or 1)
            ->when($request->user(), function($query) {
                $followedIds = $query->getModel()->newQuery()
                    ->select('follows.followed_id')
                    ->from('follows')
                    ->where('follower_id', auth()->id());
                
                // Sort by: is_promoted DESC (handled below), then is_followed DESC
                // We use a subquery for is_followed_organizer to be safe with large ID lists
                $query->addSelect(['is_followed_organizer' => function($q) use ($followedIds) {
                     $q->selectRaw('1')
                       ->from('follows')
                       ->whereColumn('follows.followed_id', 'events.organizer_id')
                       ->where('follows.follower_id', auth()->id())
                       ->limit(1);
                }]);
                
                // We must apply explicit orders here to ensure sequence
                $query->orderByDesc('is_promoted')
                      ->orderByRaw('COALESCE(is_followed_organizer, 0) DESC');
            }, function($query) {
                $query->orderByDesc('is_promoted');
            })
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();
        $featuredItems = FeaturedItem::with('event')->where('is_active', true)->orderBy('sort_order')->get();

        return view('welcome', compact('featuredEvents', 'latestEvents', 'categories', 'featuredItems'));
    }
}
