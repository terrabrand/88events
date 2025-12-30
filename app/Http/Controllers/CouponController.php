<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event); // Only organizer/admin can add coupons

        $validated = $request->validate([
            'code' => 'required|string|uppercase|alpha_dash|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'amount' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date|after:now',
        ]);

        $event->coupons()->create($validated);

        return back()->with('success', 'Coupon created successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorize('update', $coupon->event);

        $coupon->delete();

        return back()->with('success', 'Coupon deleted successfully.');
    }
}
