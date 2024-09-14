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
    public function handle(Request $request, Closure $next): Response{
        $allowedOrigins = 'http://localhost:5173';
        $origin = $request->headers->get('Origin');
        $acceptPath = $request->headers->get('Accept-Path');
        if (env('APP_ENV')==='devloppement'){
            return $next($request);
        }
        if($origin){
            if (!$acceptPath || !$origin===$allowedOrigins) {
                return response()->json(['message'=>'Unauthorized to perform this action' ], 401);
            }
            return $next($request);
        }

        return $next($request);
    }
}
