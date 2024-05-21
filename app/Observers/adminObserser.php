<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Activitie;
use App\Models\Session;
use App\Traits\Store;
use Illuminate\Support\Facades\Cookie;


class adminObserser
{
    use Store;
    public function updated(Admin $admin): void
    {     
        $profile= $admin->profile;
        $data = ['action' => 'Update', 'model' => 'Admin', 'activity'=>'Update admin','object'=>$profile->firstName .' '.$profile->lastName];
        $this->storeActivite($data);
    }

    public function forceDeleted(Admin $admin): void
    {
        //
    }
}
