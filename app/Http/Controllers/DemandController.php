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
            'email' => 'required|email'
        ]);
        $demand = new Demand();
        $demand->fullName = $request->fullName;
        $demand->email = $request->email;
        $demand->message = $request->message;
        $demand->save();
    }

    public function destroy(Demand $demand){
        $demand->delete();
    }
}
