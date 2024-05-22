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
    // public function created(Application $application): void
    // {
    //     $data = ['action' => 'Create', 'model' => 'Application', 'activity'=>'send application ','object'=>$application->offer->title ];
    //     $this->storeActivite($data);
    // }

    // public function updated(Application $application): void{
    //     $data = ['action' => 'Update', 'model' => 'Application', 'activity'=>'update application ','object'=>$application->user->firstName .' '.$application->user->lastName ];
    //     $this->storeActivite($data);
    // }

    public function deleted(Application $application): void{
        $data = [
            'action' => 'Delete', 
            'model' => 'Application', 
            'activity'=>'Deleted application for : ', 
            'object'=>$application->user->profile->firstName .' '.$application->user->profile->lastName .' --> '.$application->offer->title
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
