<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:admin') or ->middleware('role:admin,gudang')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (is_null($roles)) {
            return $next($request);
        }

        $allowed = array_map('trim', explode(',', $roles));
        $userRole = Auth::user()->role ?? null;

        if (! in_array($userRole, $allowed, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}