<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        // Get coupons for all events organized by the current user
        $coupons = Coupon::whereHas('event', function ($query) {
            $query->where('organizer_id', Auth::id());
        })->with('event')->latest()->paginate(20);

        return view('organizer.coupons.index', compact('coupons'));
    }
}
