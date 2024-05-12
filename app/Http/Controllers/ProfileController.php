<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Traits\Delete;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\Refactor;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use Refactor, Store, Delete, Update;
    public function __construct(){
        $this->middleware('role:admin|super-admin|supervisor')->only('store');
    }
    //store all users 
    public function store(Request $request) {
        $profile=$this->storeProfile($request);
        return $profile;
    }
    public function register(Request $request){
        $profile = $this->storeUser($request);
        $token = $profile->createToken('auth_token')->plainTextToken;
        $cookie = cookie('token', $token, 60 * 24); // 1 day
        return response()->json($this->refactorProfile($profile))->withCookie($cookie);
    }
//update profiles
    public function update(Request $request, string $id){
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'profile non trouvé'], 404);
        }
        $newProfile =$this->updateProfile($request,$profile);
        return $newProfile;
    }

//delete profiles
    public function destroy(string $id){
        $profile = Profile::find($id);
            if (!$profile) {
                return response()->json(['message' => 'profile non trouvé'], 404);
            }
        $isDeleted =$this->deleteProfile($profile);
        if ($isDeleted){       
            return response()->json(['message' => 'profile deleted succsfully'],200);
        }
    }
   
    public function updatePassword(Request $request,$id){
        $profile = Profile::find($id);
         if (!$profile) {
            return response()->json(['message' => 'profile non trouvé'], 404);
        }
        $isUpdated =$this->updateProfilePassword($request,$profile);
        return $isUpdated;

    }   
    public function storeAvatar(Request $request,$id){
        $profile=Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'profile non trouvé'], 404);
        }
        if (!$request->hasFile('avatar')) {
            $this->deletOldFiles($profile, 'avatar');
            return response()->json(['message' => 'avatar deleted succcefully'], 200);
        } 
        $this->storeOneFile($request,$profile,'avatar');
        return response()->json(['message' => 'new avatar added succcefully'], 200);
    }
}
