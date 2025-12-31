<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with(['event', 'user', 'package'])->latest()->paginate(20);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function updateStatus(Request $request, Promotion $promotion)
    {
        $request->validate([
            'status' => 'required|in:active,paused,ended,rejected,pending',
        ]);

        $promotion->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Promotion status updated.');
    }
}
