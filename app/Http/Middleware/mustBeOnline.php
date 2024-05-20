<?php

namespace App\Http\Middleware;

use App\Events\AuthLogout;
use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class mustBeOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
     {
        $user = auth()->user();
        $session = Session::where('profile_id', $user->id)->where('token', Cookie::get('token'))->first();
        if ($session && $session->status==='Offline'){
            auth()->logout();
            cookie()->forget('token');
            return response()->json([
                'message' => 'You are offline. Please login again.'
            ], 401)->withCookie('token');
        }
        return $next($request);
    }
}
