<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\Activitie;
use App\Models\Session;
use App\Traits\Store;
use Illuminate\Support\Facades\Cookie;

class applicationObserser
{
    use Store;
 

    public function deleted(Application $application): void{
        $user = $application->user;
        if ($user){
            $firstName=$user->profile->firstName;
            $lastName = $user->profile->lastName;
        }else{
            return;
        }
        $data = [
            'action' => 'Delete', 
            'model' => 'Application', 
            'activity'=>'Deleted application for : ', 
            'object'=>$firstName??'unknown' .' '.$lastName??'unknown' .' --> '.$application->offer->title??'unknown'
        ];

       $this->storeActivite($data);
    }

  
    public function restored(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        //
    }
}
