<?php

namespace App\Http\Controllers;

use App\Models\Profile;

use App\Traits\Refactor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use Validator;


class AuthController extends Controller
{
    use Refactor;
      public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
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
        return response()->json(['user'=>$this->refactorProfile(auth()->user())])->withCookie($logged['access_token']);
    }
// logout 
    public function logout(Request $request) {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
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

}
