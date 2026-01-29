<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is not logged in
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // Check if user has the correct role
        if ($request->user()->user_type !== $role) {
            // If customer trying to access admin, redirect to customer dashboard
            if ($role === 'admin') {
                return redirect()->route('home')->with('error', 'You do not have admin access.');
            }
            // If admin trying to access customer routes, redirect to admin dashboard
            if ($role === 'customer') {
                return redirect()->route('admin.dashboard')->with('error', 'Use admin routes for admin access.');
            }
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
