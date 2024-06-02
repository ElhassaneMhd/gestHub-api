<?php

namespace App\Http\Controllers;

use App\Traits\Delete;
use App\Traits\Get;
use App\Traits\Refactor;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;
use App\Models\Offer;


class OfferController extends Controller
{
    use Refactor, Store,Update,Delete,Get;
     public function __construct(){
        $this->middleware('role:admin|super-admin')->except(['index','show']);
    }
    public function index(){
        $offers = Offer::where("visibility",'=','Visible')->get();
        $count = count($offers);
        $limit = request()->input('limit', 10);
        $pages = ceil($count / $limit);
        $all['total'] = $count;
        $all['totalPages'] = $pages;
        foreach ($offers as $offer) {
            $all['data'][] = $this->refactorOffer($offer);
        }            
        return response()->json($all );
    }    
    public function show($id){
        return $this->GetByDataId('offers',$id);
    }
    public function store(Request $request)
    {
        // Création de l'offre de stage
        $offre = $this->storeOffer($request);
        return response()->json($this->refactorOffer($offre));
    }
    public function update(Request $request, $id)
    {
        $offer= Offer::find($id);
        if (!$offer) {
            return response()->json(['message' => 'cannot update undefined offer!'], 404);
        }
        $updatedOffer=$this->updateOffer($request,$offer);
        return response()->json($this->refactorOffer($updatedOffer));
    }
    public function destroy($id)
    {
        $offer = Offer::find($id);
        if (!$offer) {
            return response()->json(['message' => 'Ocannot delete undefined offer!'], 404);
        }
        $offer->delete();
        return response()->json(['message' => 'Offre de stage supprimée avec succès']);
    }
    public function GetByIndex(Request $request){
        $query = $request->input('title');
        $offers =  Offer::where('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->get();
        return $offers;
    }
}
