<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
        public function handle($request, Closure $next, ...$roles)
        {
            $user = Auth::user();

            if ($user && $user->hasAnyRole($roles)) {
                return $next($request);
            }

            return redirect('/login')->with('error', 'Anda tidak punya akses ke halaman ini.');
        }
}
