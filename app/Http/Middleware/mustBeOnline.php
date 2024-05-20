<?php

namespace App\Http\Middleware;

use App\Events\AuthLogout;
use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class mustBeOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
     {
        $token = Cookie::get('token');
        $session = Session::where('token', $token)->first();
        if ($session && $session->status==='Offline'){
            auth()->logout();
            cookie()->forget('token');
            return response()->json([
                'message' => 'You are offline. Please login again.'
            ], 401)->withCookie('token');
        }
        $payload = JWTAuth::manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray();

        // Get the current time and the token expiration time
        $now = Carbon::now()->timestamp;
        $exp = $payload['exp'];

        // If the token expiration is less than 10 minutes, refresh the token
        if ($exp - $now < 10 * 60) {
            $token = JWTAuth::refresh($token);
        }

        $cookie = Cookie::make('token', $token, 60);
        return $next($request)->withCookie($cookie);
   }
}
