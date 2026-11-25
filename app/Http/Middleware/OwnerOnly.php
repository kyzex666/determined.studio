<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerOnly
{
    /**
     * Handle an incoming request.
     * Owner hanya boleh melihat, tidak bisa create/update/delete
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek jika user adalah owner
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            abort(403, 'Access for Owner only.');
        }

        // Owner hanya boleh GET request (view only)
        if (!$request->isMethod('GET')) {
            abort(403, 'Owner can only view data, not modify.');
        }

        return $next($request);
    }
}