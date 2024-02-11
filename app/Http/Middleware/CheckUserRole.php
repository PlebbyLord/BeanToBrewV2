<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has the specified email
        if (auth()->check() && auth()->user()->email == 'beantobrew24@gmail.com') {
            return $next($request);
        }

        // Redirect to the home page if the user does not meet the criteria
        return redirect(route('home'));
    }
}
