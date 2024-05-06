<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = ['https://gestHub.netlify.app',"http://localhost:5173"]; 
        $origin = $request->headers->get('Origin');
        $acceptPath = $request->headers->get('Accept-Path');
        if (!in_array($origin, $allowedOrigins) || !$acceptPath) {
            return response()->json(['message'=>'Unauthorized to perform this action' ], 401);
        }
        return $next($request);
    }
}
