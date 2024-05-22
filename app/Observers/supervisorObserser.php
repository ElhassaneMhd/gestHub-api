<?php

namespace App\Observers;

use App\Models\Supervisor;
use App\Traits\Store;

class supervisorObserser
{

    use Store;
    public function updated(Supervisor $supervisor): void
    {
        $profile = $supervisor->profile;
        $data = [
            'action' => 'Update', 
            'model' => 'User', 
            'activity'=>'Updated the supervisor profile for : ', 
            'object'=>$profile->firstName .' '.$profile->lastName
        ];
        $this->storeActivite($data);

    }


    public function deleted(Supervisor $supervisor): void
    {
        //
    }


    public function restored(Supervisor $supervisor): void
    {
        //
    }

    /**
     * Handle the Supervisor "force deleted" event.
     */
    public function forceDeleted(Supervisor $supervisor): void
    {
        //
    }
}
