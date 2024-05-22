<?php

namespace App\Observers;

use App\Models\Task;
use App\Traits\Store;

class taskObserser
{
    use Store;
    public function created(Task $task): void{
        $data = ['action' => 'Create', 'model' => 'Task', 'activity'=>'Created a new task : ','object'=>$task->project->subject . '/' . $task->title];
        $this->storeActivite($data);

    }

    public function updated(Task $task): void{
        $data = ['action' => 'Update', 'model' => 'Task', 'activity'=>'Updated task : ','object'=>$task->project->subject . '/' . $task->title ];
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