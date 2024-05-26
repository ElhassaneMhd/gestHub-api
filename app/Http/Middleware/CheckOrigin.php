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
        $allowedOrigins = ["http://localhost:5173",'https://gestHub.netlify.app']; 
        $referer = $request->headers->get('Referer');
        $acceptPath = $request->headers->get('Accept-Path');
        foreach($allowedOrigins as $origin){
            if (!$acceptPath || !str_contains($referer,$origin)) {
                return response()->json(['message'=>'Unauthorized to perform this action' ], 401);
            }else{
                break;
            }
        }
        return $next($request);
    }
}
