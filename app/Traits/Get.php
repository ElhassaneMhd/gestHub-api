<?php

namespace App\Traits;
use App\Models\Admin;
use App\Models\Application;
use App\Models\Demand;
use App\Models\Intern;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Session;
use App\Models\Setting;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
trait Get
{
    use Refactor;
    public function GetAll($data,$limit){
        $profile = Auth::user();
        $all = [];
        if ($data === 'admins') {
            $admins = Admin::paginate($limit);
            foreach ($admins as $admin) {
                $profile = $admin->profile;
                if ($profile->getRoleNames()[0]!=='super-admin' ){
                    $all[]= $this->refactorProfile($profile);
                }
            }
        }
        elseif ($data === 'supervisors') {
            $supervisors = Supervisor::paginate($limit);
            foreach ($supervisors as $supervisor) {
                $profile = $supervisor->profile;
                $all[]= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'interns') {
            $interns = Intern::paginate($limit);
            foreach ($interns as $intern) {
                $profile = $intern->profile;
                $all[]= $this->refactorProfile($profile);
            }
        }
        elseif ($data === 'users') {
            $users = User::paginate($limit);
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
            if (Auth::user()->hasRole('supervisor')){
                $projects  = $profile->supervisor->projects;
            }
            if (Auth::user()->hasRole('intern')) {
                $projects  = $profile->intern->projects;
            }
             if (Auth::user()->hasRole('admin')||Auth::user()->hasRole('super-admin')) {
                $projects = Project::all();
            }
            foreach ($projects??[] as $project) {
                $all[]= $this->refactoProject($project);
            }
        }
        elseif ($data === 'tasks') {
            if (Auth::user()->hasRole('supervisor')){
                $sup = $profile->supervisor;
                $tasks = Task::whereIn('project_id',$sup->projects->pluck('id'))->get();
            }
            if (Auth::user()->hasRole('intern')){
                $intern = $profile->intern;
                $tasks = Task::whereIn('project_id',$intern->projects->pluck('id'))->get();
            }
             if (Auth::user()->hasRole('admin')||Auth::user()->hasRole('super-admin')) {
                $tasks = Task::all();
            }
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
            if (Auth::user()->hasRole('user')){
                $user = $profile->user;
                $applications = $user->applications;
            }
             if (Auth::user()->hasRole('admin')||Auth::user()->hasRole('super-admin')) {
                $applications = Application::paginate($limit);
            }
            foreach ($applications as $application) {
                $all[]= $this->refactorApplication($application);
            }            
            
        }
        elseif($data === 'settings'){
            $settings = Setting::first();
            if($settings){
                $all= $this->refactorSettings($settings);
            }
        }
        elseif($data === 'sessions'){
            if (Auth::user()->hasRole('admin')||Auth::user()->hasRole('super-admin')) {
                $sessions = Session::paginate($limit);
            }else{
                $sessions = $profile->sessions;
            }
            foreach ($sessions as $session) {
                $all[]= $this->refactorSession($session);
            }
        }
        elseif($data === 'notifications'){
            $profile = auth()->user();
            $notifications = $profile->notifications;
             foreach ($notifications as $notification) {
                $all[]= $this->refactorNotification($notification);
            }
        }
        elseif($data === 'contacts'){
            (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) ? $contacts = Demand::paginate($limit) :$contacts = [];
            foreach ($contacts as $session) {
                $all[]= $this->refactorSession($session);
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
        elseif ($data === 'sessions') {
            $session = Session::Find($id);
            if ($session){
                $results= $this->refactorSession($session);
            }
        }     
        elseif ($data === 'notifications') {
            $notification = Notification::Find($id);
            if ($notification){
                $results = $this->refactorNotification($notification);
            }
        } elseif($data === 'contacts'){
            (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) && $contact = Demand::Find($id) ;
            $results = $contact??null;
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