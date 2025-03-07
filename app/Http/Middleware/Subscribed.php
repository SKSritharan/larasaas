<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect('/login');
        }

        if ($request->user()->hasRole('admin')){
            return $next($request);
        }

        if (! ($request->user()->subscription())) {
            return redirect('/');
        }

        return $next($request);
    }
}
