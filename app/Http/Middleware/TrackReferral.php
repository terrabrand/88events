<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $referralCode = $request->query('ref');
            // Store for 30 days in a cookie, or just in session for simplicity
            // session(['referral_code' => $referralCode]);
            
            // Using cookie is better for persistence across sessions
            $response = $next($request);
            return $response->cookie('referral_code', $referralCode, 60 * 24 * 30); // 30 days
        }

        return $next($request);
    }
}
