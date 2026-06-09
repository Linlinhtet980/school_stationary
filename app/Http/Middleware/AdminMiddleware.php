<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->check() || !auth()->user()->isStaff()){
            return redirect()->route('login')->withErrors("You do not have permission to access the admin page.");
        }
        return $next($request);
    }
}
