<?php

namespace App\Http\Controllers;

use App\Models\Profile;

use App\Models\Session;
use App\Traits\Refactor;

use App\Traits\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    use Refactor,Store;
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login','register','session']]);
        $this->middleware('role:super-admin')->only('abortSession');
    }
 
// login a user methods
    public function login(LoginRequest $request) {
     $data = $request->validated();
        $profile= Profile::where('email', $data['email'])->first();
            if (!$profile) {
            return response()->json([
                'message' => "The email address you've entered does not exist. Please verify your email and try again"
            ], 401);
        }
//check if the password is correct        
        if (!Hash::check($data['password'], $profile->password)) {
            return response()->json([
                'message' => "The password you've entered is incorrect. Please check your password and try again."
            ], 401);
        }
        
        if (! $token = auth()->attempt($request->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
   
        $logged=$this->createNewToken($token);
        
        $ip=$request->header('X-Forwarded-For');
        $from = $request->header('X-From');
        $this->storeSession($profile->id, $logged['access_token'],$from,$ip);
        return response()->json($this->refactorProfile(auth()->user()))->withCookie('token',$logged['access_token']);
    }
     public function register(Request $request){
        $profile = $this->storeUser($request);
        return response()->json($this->refactorProfile($profile));
    }
// logout 
    public function logout(Request $request) {
        $session = Session::where('token', Cookie::get('token'))->first();
        $this->updateSession($session);
        auth()->logout();
        cookie()->forget('token');
        return response()->json([
            'message' => 'User successfully signed out'
        ])->withCookie('token');
    }
    public function refresh(){
        $logged= $this->createNewToken(auth()->refresh());
        return response()->json($this->refactorProfile(auth()->user()))->withCookie('token',$logged['access_token']);
    }
    public function session(){
        $session = [
           'userAgent'=> request()->userAgent(),
           'ip'=> request()->ip(),
        ];
        return response()->json($session);
    }
// get the authenticated user method
    public function user(Request $request) {
        $user = auth()->user();
        return response()->json($this->refactorProfile($user));
    }
    protected function createNewToken($token){
        return [
            'access_token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }
    public function abortSession($id){
        $session = Session::find($id);
        if(!$session){
            return response()->json(['message' => "cannot logout undefined session!!"], 404);
        }
        $session->status = 'Offline';
        $session->save();
        JWTAuth::setToken($session->token)->invalidate();
        $isLoggedOut=$session->save();
        if($isLoggedOut){
            return response()->json(['message' => 'session logouted succsfully'],200);
        }
    }
   
}
