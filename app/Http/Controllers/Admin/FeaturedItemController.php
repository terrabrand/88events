<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedItem;
use App\Models\Event;
use Illuminate\Http\Request;

class FeaturedItemController extends Controller
{
    public function index()
    {
        $items = FeaturedItem::with('event')->orderBy('sort_order')->get();
        $events = Event::where('status', 'published')->latest()->get();
        return view('admin.featured.index', compact('items', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:event,custom',
            'event_id' => 'required_if:type,event|nullable|exists:events,id',
            'title' => 'required_if:type,custom|nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'link_url' => 'required_if:type,custom|nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('featured', 'public');
        }

        FeaturedItem::create($validated);

        return back()->with('success', 'Featured item added successfully.');
    }

    public function update(Request $request, FeaturedItem $featuredItem)
    {
        $validated = $request->validate([
            'title' => 'required_if:type,custom|nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'link_url' => 'required_if:type,custom|nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('featured', 'public');
        }

        $featuredItem->update($validated);

        return back()->with('success', 'Featured item updated successfully.');
    }

    public function destroy(FeaturedItem $featuredItem)
    {
        $featuredItem->delete();
        return back()->with('success', 'Featured item removed successfully.');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');
        foreach ($order as $index => $id) {
            FeaturedItem::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
