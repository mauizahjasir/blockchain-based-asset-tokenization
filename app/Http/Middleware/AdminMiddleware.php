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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if ($request->user() && $request->user()->user_type === 'admin') {
            return $next($request); // Allow the user to access the routes
        }

        // Redirect or show an unauthorized view if the user is not an admin
        return redirect('/home')->with('errors', 'Unauthorized access');
    }
}
