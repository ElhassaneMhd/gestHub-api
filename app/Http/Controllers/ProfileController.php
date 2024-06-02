<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Traits\Delete;
use App\Traits\Get;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\Refactor;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use Refactor, Store, Delete, Update,Get;
    public function __construct(){
        $this->middleware('role:admin|super-admin')->only('store');
    }
    //store all users 
    public function store(Request $request) {
        $profile=$this->storeProfile($request);
        return $profile;
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
    public function storeFile(Request $request,$id){
        $type = $request->input('type') ;
        $profile=Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'profile non trouvé'], 404);
        }
        if (!$request->hasFile($type)) {
            $this->deletOldFiles($profile, $type);
            return response()->json(['message' => $type.' deleted succcefully'], 200);
        } 
        $this->storeOneFile($request,$profile, $type );
        return response()->json(['message' => 'new '.$type.' added succcefully'], 200);
    }
}
