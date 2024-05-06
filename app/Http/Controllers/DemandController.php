<?php

namespace App\Http\Controllers;

use App\Traits\Refactor;
use App\Models\Demand;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    use Refactor, Store,Update;
    public function __construct(){
        $this->middleware('role:user|super-admin')->only(['store','markAsRead','update']);
        $this->middleware('role:admin|super-admin')->only(['accepteDemand','destroy']);
    }
    public function store(Request $request){
        // Création de l'offre de stage
        $demand = $this->storeDemand($request);
        return $demand;
    }
    public function update(Request $request, $id){
        $demand= Demand::find($id);
        if (!$demand) {
            return response()->json(['message' => 'cannot update undefined demand!'], 404);
        }
        $updatedDemand=$this->updateDemand($request,$demand);
        return response()->json($this->refactorDemand($updatedDemand));
    }
    public function accepteDemand($id ,$traitement){
        $demand=Demand::find($id);
        if (!$demand) {
            return response()->json(['message' => 'cannot '.$traitement.' undefined demand!'], 404);
        }
        if ($demand->status !== 'Pending') {
            return response()->json(['message' => 'demand alraedy processed'], 404);
        }
        if($traitement==='approve'){
            $demand->status = 'Approved';
            $demand->save();
            return response()->json(['message' => 'demand approved succeffully'], 200);
        }
        if($traitement==='reject'){
            $demand->status='Rejected';
            $demand->save();
            return response()->json(['message' => 'demand rejected succeffully'], 200);
        }
    }
    public function markAsRead($id){
        $demand = demand::find($id);
        if (!$demand) {
            return response()->json(['message' => ' undefined demand!'], 404);
        }
        $demand->isRead = 'true';
        $demand->save();
        return response()->json(['message' => 'demand is readed now'],200);
    }
    public function destroy($id){
        $demand = demand::find($id);
        if (!$demand) {
            return response()->json(['message' => 'cannot delete undefined demand!'], 404);
        }
        $this->deletOldFiles($demand,'demandeStage');
        $demand->delete();
        return response()->json(['message' => 'Offre de stage supprimée avec succès']);
    }
}

