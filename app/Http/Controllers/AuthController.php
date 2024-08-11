<?php

namespace App\Http\Controllers;

use App\Models\Profile;

use App\Models\Session;
use App\Traits\Get;
use App\Traits\Refactor;

use App\Traits\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;


class AuthController extends Controller
{
    use Refactor,Store,Get;
    public function __construct(){
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
        
        //create personal access token
        $token = $profile->createToken('auth_token')->plainTextToken;
        
        $ip=$request->headers->get('Accept-For');
        $from=$request->headers->get('Accept-From');
        $this->storeSession($profile->id,$token,$from,$ip);
        $cookie = cookie('token', $token ,720); // 1 day
        return response()->json(['data'=>$this->refactorProfile($profile),'token'=>$token])->withCookie($cookie);

    }
     public function register(Request $request){
        $profile = $this->storeUser($request);
        $token = $profile->createToken('auth_token')->plainTextToken;
        $ip=$request->headers->get('Accept-For');
        $from=$request->headers->get('Accept-From');
        $this->storeSession($profile->id,$token,$from,$ip);
        $cookie = cookie('token', $token,720 ); // 1 day

        return response()->json($this->refactorProfile($profile))->withCookie($cookie);;
    }
// logout 
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        cookie()->forget('token');
        return response()->json([
            'message' => 'Logged out successfully!'
        ])->withCookie('token');
    }

    public function user(Request $request) {
        $user = auth()->user();
        return response()->json($this->refactorProfile($user));
    }

    public function abortSession($id){
        $session = Session::find($id);
        if(!$session){
            return response()->json(['message' => "cannot logout undefined session!!"], 404);
        }
        $session->status = 'Offline';
        $session->save();
        $isLoggedOut=$session->save();
        if($isLoggedOut){
            return response()->json(['message' => 'session logouted succsfully'],200);
        }
    }
   
}
