<?php

namespace App\Observers;

use App\Models\Intern;
use App\Traits\Store;

class internObserser
{
    use Store;
    public function updated(Intern $intern): void
    {
        $profile = $intern->profile;
        $data = ['action' => 'Update', 'model' => 'User', 'activity'=>'update user ','object'=>$profile->firstName .' '.$profile->lastName ];
        $this->storeActivite($data);
    }
    public function deleted(Intern $intern): void
    {
        //
    }

    public function restored(Intern $intern): void
    {
        //
    }

    /**
     * Handle the Intern "force deleted" event.
     */
    public function forceDeleted(Intern $intern): void
    {
        //
    }
}
