<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRwPermission
{
    public function handle($request, Closure $next, string $permission)
    {   
        /** @var User $user */
        $user = Auth::user();

        if (! $user || ! $user->canRw($permission)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
