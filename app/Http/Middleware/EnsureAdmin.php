<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            if ($request->user()?->isCustomer()) {
                return redirect()->route('dashboard')->with('error', 'You do not have admin access.');
            }

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
