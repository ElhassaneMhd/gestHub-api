<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Traits\Delete;
use App\Traits\Refactor;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;

class taskController extends Controller
{
    use Store, Refactor,Update,Delete;
    public function __construct(){
        $this->middleware('role:super-admin|supervisor|intern');
    }
    public function store(Request $request)
    {
        $task = $this->storeTask($request);
        if (!$task) {
            return response()->json(['message' => "error ,Try Again"], 404);
        }
        return response()->json($this->refactorTask($task));
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => "cannot update undefined task!!"], 404);
        }
        $updated = $this->updateTask($request, $task);
        return response()->json($this->refactorTask($updated));
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => "cannot delete undefined task!!"], 404);
        }
        $isDeleted = $this->deleteTask($task);
        if ($isDeleted) {
            return response()->json(['message' => 'task deleted succsfully'], 200);
        }
    }

}