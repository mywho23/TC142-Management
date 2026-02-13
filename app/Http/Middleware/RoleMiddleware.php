<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            abort(404);
        }

        $userRole = Auth::user()->role->nama;

        if (!in_array($userRole, $roles)) {
            // redirect ke halaman custom
            return response()->view('pages.errors.forbidden', [], 404);
        }

        return $next($request);
    }
}
