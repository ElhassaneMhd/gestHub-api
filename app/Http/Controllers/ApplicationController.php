<?php

namespace App\Http\Controllers;

use App\Traits\Refactor;
use App\Models\Application;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use Refactor, Store,Update;
    public function __construct(){
        $this->middleware('role:user|super-admin')->only(['store','markAsRead','update']);
        $this->middleware('role:admin|super-admin')->only(['accepteApplication','destroy']);
    }
    public function store(Request $request){
        // Création de l'offre de stage
        $application = $this->storeApplication($request);
        return $application;
    }
    public function update(Request $request, $id){
        $application= Application::find($id);
        if (!$application) {
            return response()->json(['message' => 'cannot update undefined application!'], 404);
        }
        $updatedApplication=$this->updateApplication($request,$application);
        return response()->json($this->refactorApplication($updatedApplication));
    }
    public function accepteApplication($id ,$traitement){
        $application=Application::find($id);
        if (!$application) {
            return response()->json(['message' => 'cannot '.$traitement.' undefined application!'], 404);
        }
        if ($application->status !== 'Pending') {
            return response()->json(['message' => 'application alraedy processed'], 404);
        }
        if($traitement==='approve'){
            $application->status = 'Approved';
            $application->save();
            return response()->json(['message' => 'application approved succeffully'], 200);
        }
        if($traitement==='reject'){
            $application->status='Rejected';
            $application->save();
            return response()->json(['message' => 'application rejected succeffully'], 200);
        }
    }
    public function markAsRead($id){
        $application = application::find($id);
        if (!$application) {
            return response()->json(['message' => ' undefined application!'], 404);
        }
        $application->isRead = 'true';
        $application->save();
        return response()->json(['message' => 'application is readed now'],200);
    }
    public function destroy($id){
        $application = application::find($id);
        if (!$application) {
            return response()->json(['message' => 'cannot delete undefined application!'], 404);
        }
        $this->deletOldFiles($application,'applicationeStage');
        $application->delete();
        return response()->json(['message' => 'Offre de stage supprimée avec succès']);
    }
}

