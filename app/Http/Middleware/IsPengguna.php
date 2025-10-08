<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsPengguna
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'pengguna') {
            return $next($request);
        }

        abort(403, 'Unauthorized: hanya untuk pengguna.');
    }
}
