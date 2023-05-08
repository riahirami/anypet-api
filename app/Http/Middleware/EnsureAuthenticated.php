<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if(!$user){
            return \response()->json([
                'status' => 'ERROR',
                'message' => 'Unauthorized access'
            ],Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
