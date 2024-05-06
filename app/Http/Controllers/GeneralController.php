<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Get;
use App\Traits\Store;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    use Get,Store;
    
    public function __construct(){
        $this->middleware('role:admin|super-admin')->only('setAppSettings');
        $this->middleware('role:admin|super-admin|supervisor')->only(['getAcceptedUsers','storeNewIntern']);
    }
    public function index($data){
        return $this->GetAll($data);
    }
    public function show($data,$id){
        return $this->GetByDataId($data,$id);
    } 
    public function setAppSettings(Request $request){  
        return response()->json($this->storAppSettings($request));
    }
    public function getAcceptedUsers(){  
        return $this->getAllAcceptedUsers();
    }
    public function storeNewIntern(Request $request){
        $ids = $request['ids'];
        foreach($ids as $id){
            $user = User::find($id);
            if(!$user){
                return response()->json(['message' => 'user non trouvé'], 404);
            }
            $this->storInternFromUser($user);
        }
        return response()->json(['message' => 'interns stored successfully'],200);
    }
}
