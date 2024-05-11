<?php

namespace App\Http\Controllers;
use App\Models\Intern;
use App\Traits\Refactor;
use App\Traits\Store;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class AttestationController extends Controller
{
  use Refactor,Store;
     public function __construct(){
        $this->middleware('role:admin|super-admin')->only('generatAttestation');
    }
      public function showView($id,$attestation){
      return view('attestations.attestation'.$attestation);
    }
      public function generateAttestation($id){
          $profile = Intern::find($id)->profile;
          $intern = $this->refactorProfile($profile);
          $unique = uniqid();
          if (date('Y-m-d') < $intern['endDate']){
            return response()->json(['messsage' => 'the end stage date is not yet'], 400);
        }
        view()->share('attestations.attestation',$intern);
        $pdf = Pdf::loadView('attestations.attestation', $intern);
        
        if ($profile->files->count()>0){
            $this->deletOldFiles($profile, 'attestation');
        }
         $profile->files()->create(
            ['url' =>"/attestation/{$unique}{$intern['firstName']}{$intern['firstName']}.pdf",
                'type' => 'attestation']
        );
        $pdf->save(public_path("attestation/{$unique}{$intern['firstName']}{$intern['firstName']}.pdf"));
    return true;
    }
    public function generateAttestations(Request $request){
    $ids = $request['ids'];
      foreach($ids as $id){
        $this->generateAttestation($id);
      }
    }

}
