<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $token = Cookie::get('token');
        $session = Session::where('token', $token)->first();

        // session_start();
        // $timeout = 1800;
        // if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
        //     session_unset();     
        //     session_destroy();
        //     $session->status = 'Offline';
        //     $session->save();
        //     auth()->logout();
        //     cookie()->forget('token');
        //     return response()->json([
        //         'message' => 'You are offline. Please login again.'
        //     ], 401)->withCookie('token');
  
        // }

        // $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp4

        if ($session && $session->status==='Offline'){
            $request->user()->currentAccessToken()->delete();
            auth()->logout();
            cookie()->forget('token');
            return response()->json([
                'message' => 'You are offline. Please login again.'
            ], 401)->withCookie('token');
        }

        return $next($request);
   }
}
