<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AdminMiddleware class.
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            if (Auth::user()?->role === 'admin') {
                return $next($request);
            }
        } catch (\Exception $e) {
            // Handle exception
        }

        return redirect('/home');
    }
}