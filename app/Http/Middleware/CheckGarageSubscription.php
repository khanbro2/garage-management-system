<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGarageSubscription
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $garage = $user->garage;

        if (!$garage) {
            return redirect()->route('subscription.required')
                ->with('error', 'No garage found.');
        }

        $subscription = $garage->currentSubscription;

        if (!$subscription || !$subscription->isActive()) {
            return redirect()->route('subscription.expired')
                ->with('error', 'Your subscription has expired. Please renew to continue.');
        }

        if ($subscription->daysUntilExpiry() <= 7) {
            session()->flash('warning', 'Your subscription expires in ' . $subscription->daysUntilExpiry() . ' days. Please renew.');
        }

        return $next($request);
    }
}