<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class
CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $adminRole= "2";
        $userRole= "1";
        if ($request->user() && $request->user()->role_id == $adminRole) {
            return $next($request);
        }
        // Redirect or return a response for unauthorized access
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
