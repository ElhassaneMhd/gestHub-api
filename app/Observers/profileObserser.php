<?php

namespace App\Observers;

use App\Models\Profile;
use App\Models\Session;
use App\Models\Activitie;
use App\Traits\Store;
use Illuminate\Support\Facades\Cookie;

class profileObserser
{
    use Store;

    public function created(Profile $profile): void{
        $role = request('role');  
        $data = ['action' => 'Create', 'model' => ucfirst($role) , 'activity'=>'craete profile ','object'=>$profile->firstName .' '.$profile->lastName ];
        $this->storeActivite($data);

    }

    public function updated(Profile $profile): void{
        $role = $profile->getRoleNames()[0];
        $data = ['action' => 'Update', 'model' => $role, 'activity'=>'update ' . ucfirst($role),'object'=>$profile->firstName .' '.$profile->lastName ];
        $this->storeActivite($data);    
    }

    public function deleted(Profile $profile): void{
         $data = ['action' => 'Delete', 'model' => ucfirst( $profile->getRoleNames()[0]), 'activity'=>'delete user ' ,'object'=>$profile->firstName .' '.$profile->lastName ];
        $this->storeActivite($data); 
    }

    public function restored(Profile $profile): void
    {
        //
    }


    public function forceDeleted(Profile $profile): void
    {
        //
    }
}
