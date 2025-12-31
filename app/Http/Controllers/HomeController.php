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
            ->withPromotionStatus()
            ->orderByDesc('is_promoted')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();
        $featuredItems = FeaturedItem::with('event')->where('is_active', true)->orderBy('sort_order')->get();

        return view('welcome', compact('featuredEvents', 'latestEvents', 'categories', 'featuredItems'));
    }
}
