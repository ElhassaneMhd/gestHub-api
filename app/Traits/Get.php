<?php

namespace App\Traits;
use App\Models\Admin;
use App\Models\Application;
use App\Models\Intern;
use App\Models\Offer;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
trait Get
{
    use Refactor;
    public function GetAll($data){
        $all = [];
        if ($data === 'admins') {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $profile = $admin->profile;
                $all[]= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'supervisors') {
            $supervisors = Supervisor::all();
            foreach ($supervisors as $supervisor) {
                $profile = $supervisor->profile;
                $all[]= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'interns') {
            $interns = Intern::all();
            foreach ($interns as $intern) {
                $profile = $intern->profile;
                $all[]= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'users') {
            $users = User::all();
            foreach ($users as $user) {
                $profile = $user->profile;
                $all[]= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'profiles') {
            $profiles = Profile::all();
            foreach ($profiles as $profile) {
                $all[]= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'projects') {
            $projects = Project::all();
            foreach ($projects as $project) {
                $all[]= $this->refactoProject($project);
            }
        }
        elseif ($data === 'tasks') {
            $tasks = Task::all();
            foreach ($tasks as $task) {
                $all[]= $this->refactorTask($task);
        }            
        }
        elseif ($data === 'offers') {
            $offers = Offer::all();
            foreach ($offers as $offer) {
                $all[]= $this->refactorOffer($offer);
          }            
        }
        elseif ($data === 'applications') {
            $user = auth()->user();
            $applications = Application::all();
            foreach ($applications as $application) {
                $all[]= $this->refactorApplication($application);
            }            
            
        }elseif($data === 'settings'){
            $settings = Setting::first();
            if($settings){
                $all= $this->refactorSettings($settings);
            }
        }

        if(isset($all) ){
            return response()->json($all);
        }
        else{
            return response()->json($all, 200);
        }
    }
    public function GetByDataId($data,$id){
        if ($data === 'admins') {
            $admin = Admin::Find($id);
            if ($admin){
                $profile = $admin->profile;
                $results= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'interns') {
            $intern = Intern::Find($id);
            if ($intern){
                $profile = $intern->profile;
                $results= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'supervisors') {
            $ssupervisor = Supervisor::Find($id);
            if ($ssupervisor){
                $profile = $ssupervisor->profile;
                $results= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'users') {
            $user = User::Find($id);
            if ($user){
                $profile = $user->profile;
                $results= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'profiles') {
            $profile = Profile::Find($id);
            if ($profile){
                $results= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'projects') {
            $project = Project::Find($id);
            if ($project){
                $results= $this->refactoProject($project);
            }
        }
        elseif ($data === 'tasks') {
            $task = Task::Find($id);
            if ($task){
                $results= $this->refactorTask($task);
            }
        } 
        elseif ($data === 'applications') {
            $application = Application::find($id);
            if ($application){
                $results= $this->refactorApplication($application);
            }
        }    
        elseif ($data === 'offers') {
            $offer = Offer::Find($id);
            if ($offer){
                $results= $this->refactorOffer($offer);
            }
        }     
        else{
            return response()->json(['message' => 'Looking for undefined api'], 404);
        }        
        if(empty($results)){
            return response()->json(['message' => 'Looking for undefined data, try with a different id'], 404);
        }
        return response()->json($results);
    }
    public function getAllAcceptedUsers(){
        $users = User::all();
        foreach($users as $user){
            if (count($user->applications->where('status','=','Approved'))>0 ){
                $profile = $user->profile;
                $accptedUsers[] = $this->refactorProfile($profile);
            }
        }
        return response()->json($accptedUsers??[],200);
    }
}