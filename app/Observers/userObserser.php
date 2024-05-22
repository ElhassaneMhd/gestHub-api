<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Activitie;
use App\Models\Session;
use App\Traits\Store;
use Illuminate\Support\Facades\Cookie;
class userObserser
{

    use Store;
    public function updated(User $user): void{
        $profile = $user->profile;
        $data = [
            'action' => 'Update', 
            'model' => 'User', 
            'activity'=>'Updated the user profile for : ', 
            'object'=>$profile->firstName .' '.$profile->lastName
        ];        $this->storeActivite($data);
    }


    public function restored(User $user): void
    {
        //
    }


    public function forceDeleted(User $user): void
    {
        //
    }
}
