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
        $allowedOrigins = ['https://gesthub.netlify.app',"http://localhost:5173"]; 
        $origin = $request->headers->get('Origin');
        $referer = $request->headers->get('Referer');
        $acceptPath = $request->headers->get('Accept-Path');
        if($origin){
            if (!$acceptPath || !in_array($origin,$allowedOrigins)) {
                return response()->json(['message'=>'Unauthorized to perform this action' ], 401);
            }
            return $next($request);     
        }
        if  (str_contains($referer,'localhost') ){
                if (!$acceptPath || !str_contains($referer, "http://localhost:5173")) {
                    return response()->json(['message' => 'Unauthorized to perform this action in localhost'], 401);
                } 
    
        }
        return $next($request);
    }
}
