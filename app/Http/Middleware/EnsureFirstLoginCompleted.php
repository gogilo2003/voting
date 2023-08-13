<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFirstLoginCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->first_login_completed) {
            // Redirect the user to the profile picture and password update pages
            return redirect()->route('profile.show')->with('warning', 'Please ensure to upload a profile picture and update password');
        }
        return $next($request);
    }
}
