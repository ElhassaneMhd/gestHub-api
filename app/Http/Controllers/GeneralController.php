<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Intern;
use App\Models\Profile;
use App\Models\User;
use App\Traits\Delete;
use App\Traits\Get;
use App\Traits\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    use Get,Store,Delete;
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
    }
    public function multipleActions(Request $request,$data,$action){
        $ids = $request['ids'];
        if (in_array($data,['supervisors','interns','admins'] )&&$action==='delete' ){    
            DB::beginTransaction();
            foreach ($ids as $id){
                if ( !$profile = Profile::find($id)){
                    DB::rollBack();
                    return response()->json(['message' => 'profile non trouvé'], 404);
                }
                $this->deleteProfile($profile);
            }
            DB::commit();
            return response()->json(['message' => count($ids).' profiles deleted succefully' ], 200);
        }
        if ($data=="applications" && in_array($action,['approve','reject'])){
            DB::beginTransaction();
            foreach ($ids as $id){
                if ( !$application = Application::find($id)){
                    DB::rollBack();
                    return response()->json(['message' => 'application non trouvé'], 404);
                }
                    $this->processApplication($application,$action);
            }
            DB::commit();
            return response()->json(['message' => count($ids).'applications processed succefully' ], 200);
        }
        if ($data == "applications" && $action== 'delete') {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $application = application::find($id);
                if (!$application) {
                    DB::rollBack();
                    return response()->json(['message' => 'cannot delete undefined application!'], 404);
                }
                $this->deletOldFiles($application, 'applicationeStage');
                $application->delete();
            }
            DB::commit();
            return response()->json(['message' => count($ids).'applications deleted succefully' ], 200);
        }
        if ($data=="attestations" && $action=='generate'){
            DB::beginTransaction();
            foreach ($ids as $id){
                if ( !Intern::find($id)){
                    DB::rollBack();
                    return response()->json(['message' => 'intern non trouvé'], 404);
                }
                $this->generateAttestation($id);
            }
            DB::commit();
            return response()->json(['message' => count($ids).' attestations generated succefully' ], 200);
        }
        if ($data =='users' && $action==='accept'){
            DB::beginTransaction();
            foreach($ids as $id){
                $user = User::find($id);
                if(!$user){
                    DB::rollBack();
                    return response()->json(['message' => 'user non trouvé'], 404);
                }
                $this->storInternFromUser($user);
            }
            DB::commit();
            return response()->json(['message' =>count($ids).' interns stored successfully'],200);
        }
    }
}
