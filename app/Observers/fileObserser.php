<?php

namespace App\Observers;

use App\Models\File;
use App\Traits\Store;

class fileObserser
{
    use Store;

    public function created(File $file): void
    {
        $data = ['action' => 'Upload', 'model' => 'File ', 'activity'=>'store ','object'=>$file->type ];
        $this->storeActivite($data);
    }

  
    public function updated(File $file): void
    {
        $data = ['action' => 'Update', 'model' => 'File', 'activity'=>'update file ','object'=>$file->type ];
        $this->storeActivite($data);

    }

   
    public function deleted(File $file): void
    {
        $data = ['action' => 'Delete', 'model' => 'File', 'activity'=>'remove  ','object'=>$file->type];
        $this->storeActivite($data);
    }

    /**
     * Handle the File "restored" event.
     */
    public function restored(File $file): void
    {
        //
    }

    /**
     * Handle the File "force deleted" event.
     */
    public function forceDeleted(File $file): void
    {
        //
    }
}
