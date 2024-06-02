<?php

namespace App\Http\Controllers;
use App\Models\Demand;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    public function store(Request $request)
    {
        $countDemand = Demand::all()->count();
        if($countDemand > 30){
            $firstDemand = Demand::first();
            $firstDemand->delete();
        }
        $request->validate([
            'fullName' => 'required',
            'message' => 'required',
            'subject' => 'required',
            'email' => 'required|email'
        ]);
        $demand = new Demand();
        $demand->fullName = $request->fullName;
        $demand->email = $request->email;
        $demand->subject = $request->subject;
        $demand->message = $request->message;
        $demand->save();
    }
    public function destroy($id){
        $demand = Demand::find($id);
        if (!$demand) {
            return response()->json(['message' => 'Ocannot delete undefined demand!'], 404);
        }
        $demand->delete();
        return response()->json(['message' => 'Demand de stage supprimée avec succès']);
    }
}
