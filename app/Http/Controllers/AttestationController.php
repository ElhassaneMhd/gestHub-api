<?php

namespace App\Http\Controllers;
use App\Models\Intern;
use App\Traits\Get;
use App\Traits\Refactor;
use App\Traits\Store;
use Illuminate\Http\Request;


class AttestationController extends Controller
{
  use Refactor,Store,Get;
    public function __construct(){
        $this->middleware('role:admin|super-admin')->only('generatAttestation');
    }
    public function showView($id,$attestation){
      return view('attestations.attestation'.$attestation);
    }
    public function generateOneAttestation($id){
      $this->generateAttestation($id);

    }
}
