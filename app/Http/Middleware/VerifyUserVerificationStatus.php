<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyUserVerificationStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and their verification_status is 0
        if (auth()->check() && auth()->user()->verification_status === 0) {
            // Redirect the user to the home page with a flash message
            return redirect('/verification/form')->with('error', 'You must verify your account to access this page.');
        }

        // Allow the user to proceed to the requested route
        return $next($request);
    }
}
