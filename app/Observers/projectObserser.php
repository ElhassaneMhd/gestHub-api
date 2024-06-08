<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\Activitie;
use App\Models\Session;
use App\Traits\Store;
use Illuminate\Support\Facades\Cookie;

class projectObserser
{
    use Store;
    public function created(Project $project): void
    {
        $data = ['action' => 'Create', 'model' => 'Project', 'activity'=>'Created a new project : ','object'=>$project->subject ];
            $notifData = [
                'activity'=>'You have been assigned a new project',
                'object'=>$project->subject,
                'action'=>'newProject',
                'receiver'=>$project->supervisor->profile->id
            ];
            $this->storeNotification($notifData);
        $this->storeActivite($data);
    }

    public function updated(Project $project): void
    {
        $teamMembers = $project->interns;
        $supervisor = $project->supervisor;
        $data = ['action' => 'Update', 'model' => 'Project', 'activity'=>'Updated project : ','object'=>$project->subject ];
            foreach($teamMembers as $teamMember){
                $notifData = [
                    'activity'=>'One of your assigned projects is completed',
                    'object'=>$project->subject,
                    'action'=>'completedProject',
                    'receiver'=>$teamMember->profile->id
                ];
                $this->storeNotification($notifData);
            }
             $notifData = [
                    'activity'=>'One of your assigned projects is completed',
                    'object'=>$project->subject,
                    'action'=>'completedProject',
                    'receiver'=>$supervisor->profile->id
                ];
                $this->storeNotification($notifData);
        $this->storeActivite($data);
    }

    public function deleted(Project $project): void
    {
        $data = ['action' => 'Delete', 'model' => 'Project', 'activity'=>'Deleted project : ','object'=>$project->subject ];
        $this->storeActivite($data);
    }

    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
