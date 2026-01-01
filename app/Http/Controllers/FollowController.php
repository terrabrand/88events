<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if (!Auth::user()->isFollowing($user)) {
            Auth::user()->following()->attach($user->id);
        }

        return back()->with('success', 'You are now following ' . $user->name);
    }

    public function destroy(User $user)
    {
        if (Auth::user()->isFollowing($user)) {
            Auth::user()->following()->detach($user->id);
        }

        return back()->with('success', 'You have unfollowed ' . $user->name);
    }
}
