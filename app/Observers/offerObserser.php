<?php

namespace App\Observers;

use App\Models\Activitie;
use App\Models\Offer;
use App\Models\Session;
use App\Traits\Store;
use Illuminate\Support\Facades\Cookie;

class offerObserser
{
    use Store;
    public function created(Offer $offer): void
    {
        $data = [
            'action' => 'Create', 
            'model' => 'Offer', 
            'activity'=>'Created a new offer : ', 
            'object'=>$offer->title 
        ];
        $this->storeActivite($data);
    }

    public function updated(Offer $offer): void
    {
        $data = [
            'action' => 'Update', 
            'model' => 'Offer', 
            'activity'=>'Updated offer : ', 
            'object'=>$offer->title 
        ];
        $this->storeActivite($data);
    }

    public function deleted(Offer $offer): void
    {
        $data = [
            'action' => 'Delete', 
            'model' => 'Offer', 
            'activity'=>'Deleted offer : ', 
            'object'=>$offer->title 
        ];
        $this->storeActivite($data);
    }
    public function restored(Offer $offer): void
    {
        //
    }

    /**
     * Handle the Offer "force deleted" event.
     */
    public function forceDeleted(Offer $offer): void
    {
        //
    }
}
