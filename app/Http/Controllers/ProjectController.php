<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Traits\Delete;
use App\Traits\Refactor;
use App\Traits\Store;
use App\Traits\Update;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use Refactor,Store,Update,Delete;
      public function __construct(){
        $this->middleware('role:admin|super-admin');
        $this->middleware('role:supervisor')->except('store');
    }
    public function store(Request $request)
    {
        $project = $this->storeProject($request);
        if (!$project) {
            return response()->json(['message' => "error ,Try Again"], 404);
        }        
        return response()->json($this->refactoProject($project) );
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);
          if (!$project) {
            return response()->json(['message' => "cannot update undefined project!!"], 404);
        }
        $updated = $this->updateProject($request,$project);
        return response()->json($this->refactoProject($updated) );
    }

    public function destroy($id)
    {
        $project = Project::find($id);
          if (!$project) {
            return response()->json(['message' => "cannot delete undefined project!!"], 404);
        }
        $isDeleted = $this->deleteProject($project);
        if ($isDeleted){       
        return response()->json(['message' => 'project deleted succsfully'],200);
    }  
  }
}
