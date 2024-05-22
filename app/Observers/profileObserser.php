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

    public function created(Profile $profile): void
    {
        $role = request('role');  
        $data = [
            'action' => 'Create', 
            'model' => ucfirst($role), 
            'activity'=>'Created a new ' . $role . ' profile for : ', 
            'object'=>$profile->firstName .' '.$profile->lastName 
        ];
        $this->storeActivite($data);
    }

    public function updated(Profile $profile): void
    {
        $role = $profile->getRoleNames()[0];
        $data = [
            'action' => 'Update', 
            'model' => ucfirst($role), 
            'activity'=>'Updated the ' . $role . ' profile for : ', 
            'object'=>$profile->firstName .' '.$profile->lastName 
        ];
        $this->storeActivite($data);    
    }

    public function deleted(Profile $profile): void
    {
        $role = $profile->getRoleNames()[0];
        $data = [
            'action' => 'Delete', 
            'model' => ucfirst($role), 
            'activity'=>'Deleted the ' . $role . ' profile for : ', 
            'object'=>$profile->firstName .' '.$profile->lastName 
        ];
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
