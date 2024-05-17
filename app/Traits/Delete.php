<?php

namespace App\Traits;
use App\Models\File;

trait Delete
{
    public function deleteProfile($profile){
        foreach(['avatar',"cv","attestation",'report'] as $file){
            $this->deletOldFiles($profile, $file);
        }
        if ($profile->getRoleNames()[0]==='intern'){
            $intern = $profile->intern;
            $applications = $intern->applications;
            foreach ($applications as $application){
                    $this->deletOldFiles($application, 'applicationStage');
                    $application->delete();
                }
        }
        if ($profile->getRoleNames()[0]==='supervisor'){
            $supervisor = $profile->supervisor;
            $projects = $supervisor->projects;
            foreach ($projects as $project){
                $project->supervisor_id = null;
                $project->save();
            }
        }

        if($profile->delete()){
            return true;
        }
    }
    public function deleteProject($project){  
    $project->interns()->detach();
    if($project->delete()){ 
            return true;
        }
    }
    public function deleteTask($task){
        $project_id = $task->project_id;
        if($task->delete()){ 
            $this->updateProjectStatus($project_id);
                return true;
            }
    }
    public function deletOldFiles($element,$fileType){
        $oldFile = $element->files->where('type','=',$fileType)->first();
        if ($oldFile){
            File::find($oldFile->id)->delete();
        }
        if ($oldFile&&\File::exists(public_path($oldFile->url))){
                \File::delete(public_path($oldFile->url));
        }
    }
}