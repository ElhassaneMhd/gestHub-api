<?php

namespace App\Observers;

use App\Models\Task;
use App\Traits\Store;

class taskObserser
{
    use Store;
    public function created(Task $task): void{
        $profile = $task->intern->profile;
        $data = ['action' => 'Create', 'model' => 'Task', 'activity'=>'Created a new task : ','object'=>$task->project->subject . '/' . $task->title];
        
        $notifData = [
            'activity'=>'You have been assigned a new task',
             'object'=>$task->project->subject. '/' . $task->title,
             'action'=>'newTask',
            'receiver'=>$profile->id
            ];
        $this->storeNotification($notifData);

        $this->storeActivite($data);

    }

    public function updated(Task $task): void{
        $data = ['action' => 'Update', 'model' => 'Task', 'activity'=>'Change task status to : '. $task->status,'object'=>$task->project->subject . '/' . $task->title ];
        $this->storeActivite($data);
    }

    public function deleted(Task $task): void{
        $data = ['action' => 'Delete', 'model' => 'Task', 'activity'=>'Deleted task : ','object'=>$task->project->subject . '/' . $task->title ];
        $this->storeActivite($data);
    }

    public function restored(Task $task): void{
        //
    }
    public function forceDeleted(Task $task): void{
        //
    }
}
