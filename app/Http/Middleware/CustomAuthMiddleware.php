<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated based on session data
        if (session()->has('user_details')) {
            // User is authenticated, proceed to the next middleware or route
            return $next($request);
        }

        // User is not authenticated, redirect to the login page or perform any other action
        return redirect('/');
    }
}
