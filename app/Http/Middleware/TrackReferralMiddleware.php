<?php

namespace App\Http\Middleware;

use App\Models\ResellerProfile;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackReferralMiddleware
{
    /**
     * Handle an incoming request and track reseller referral codes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($refCode = $request->query('ref')) {
            $refCode = trim($refCode);

            // Find by profile referral code or phone
            $profile = ResellerProfile::where('referral_code', $refCode)->first();
            $reseller = $profile ? $profile->user : User::where('phone', $refCode)->where('role', 'reseller')->first();

            if ($reseller && $reseller->isReseller() && $reseller->isActive()) {
                session([
                    'referral_reseller_id' => $reseller->id,
                    'referral_code' => $refCode,
                ]);

                $response = $next($request);
                return $response->withCookie(cookie('referral_reseller_id', (string) $reseller->id, 60 * 24 * 30));
            }
        }

        // Check if cookie exists and session doesn't have it
        if (!session()->has('referral_reseller_id') && ($cookieResellerId = $request->cookie('referral_reseller_id'))) {
            session(['referral_reseller_id' => (int) $cookieResellerId]);
        }

        return $next($request);
    }
}
