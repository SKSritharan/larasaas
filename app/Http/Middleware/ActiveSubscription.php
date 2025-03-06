<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->hasRole('admin')){
            return $next($request);
        }

        if (! ($request->user()->subscription()->active())) {
            return redirect('/billing');
        }

        return $next($request);
    }
}
