<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureGarageAccess
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

        if (!$user->garage_id) {
            abort(403, 'No garage assigned to your account.');
        }

        if (!$user->garage->isActive()) {
            abort(403, 'Your garage account is not active.');
        }

        return $next($request);
    }
}