<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isCustomer()) {
            if ($request->user()?->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
