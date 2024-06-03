<?php

namespace App\Http\Controllers;

use App\Traits\Get;
use App\Traits\Refactor;
use App\Models\Application;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use Refactor, Store,Update,Get;
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
        $this->processApplication($application,$traitement);
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

