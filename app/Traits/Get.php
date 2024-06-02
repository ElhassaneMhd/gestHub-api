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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
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
                $all[]=$contacts ;
        }
        elseif($data==='sectors'){
            $sectors = Offer::all()->pluck('sector');
            $all=$sectors;
        }
        elseif($data==='cities'){
            $cities = Offer::all()->pluck('city');
            $all = $cities;
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
    Public function getAllStats(){
        $profile = Auth::user();
        function getLengthElement($element){
            return DB::table($element)->count();
        }
        function reafctorDashboardTasks($allTasks){
            foreach($allTasks as $task){
                $tasks[]= ['created_at'=>$task->created_at,'updated_at'=>$task->updated_at,'status'=>$task->status,'dueDate'=>$task->dueDate] ;
             }
            return $tasks;
        }
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) {

            $admins = getLengthElement('admins');
            $interns = getLengthElement('interns');
            $supervisors = getLengthElement('supervisors');
            $personnel = compact('admins','interns','supervisors');

            $totalApplications = getLengthElement('applications');
            $pendingApplications = Application::where('status','Pending')->count();
            $approvedApplications = Application::where('status','Approved')->count();
            $rejectedApplications = Application::where('status','Rejected')->count();
            $applications = compact('totalApplications','pendingApplications','approvedApplications','rejectedApplications');

            $totalOffers = getLengthElement('offers');

            $latestOffers = Offer::withCount('applications')->get()->sortBy('applications_count')->take(7)->map(function ($offer) {
                $approved = 0;
                $rejected = 0;
                foreach ($offer->applications as $application) {
                    $application->status==='Approved' && $approved += 1;
                    $application->status==='Rejected' && $rejected += 1;
                }
                if($offer->applications_count>0){
                    return [
                        'name' => $offer->title,
                        'applications' => compact('approved','rejected')         
                    ];
                }
            })->values();
            $offers = compact('totalOffers','latestOffers');
        
            $allTasks = Task::all();
            $totalTasks = getLengthElement('tasks');
            $toDoTasks = Task::where('status', 'To Do')->count(); 
            $inProgressTasks = Task::where('status', 'In Progress')->count(); 
            $doneTasks = Task::where('status', 'Done')->count(); 
            $overdueTasks = Task::where('dueDate','<', Carbon::now())->count();
            $tasks = reafctorDashboardTasks($allTasks);
            $tasks = compact('tasks','totalTasks','toDoTasks','inProgressTasks','doneTasks','overdueTasks');

            $totalProjects = getLengthElement('projects');
            $completedProjects = Project::where('status', 'Completed')->count();
            $inProgressProjects = Project::where('status', 'In Progress')->count();
            $notStartedProjects = Project::where('status', 'Not Started')->count();
            $overdueProjects = Project::where('endDate','<' ,Carbon::now())->count();
            $projects = compact('totalProjects','completedProjects','notStartedProjects','inProgressProjects','overdueProjects');
      
            return compact('personnel','projects','offers','applications','tasks');
        }
        if(Auth::user()->hasRole('intern')) {
            $allTasks = Auth::user()->intern->tasks;
            $totalTasks = Auth::user()->intern->tasks->count();
            $completedTasks = Task::where('intern_id', $profile->intern->id)->where('status', 'Done')->count();
            $overdueTasks = Task::where('intern_id', $profile->intern->id)->where('dueDate','<', Carbon::now())->count();
            $totalProjects = Auth::user()->intern->projects->count();
            $tasks = reafctorDashboardTasks($allTasks);
            $tasks = compact('totalTasks',"completedTasks",'overdueTasks','tasks');
            return compact('totalProjects','tasks');
        }
        if (Auth::user()->hasRole('supervisor')) {
            $totalProjects = Auth::user()->supervisor->projects->count();
            $allProjects = Auth::user()->supervisor->projects;
            $completedProjects = Project::where('supervisor_id', $profile->supervisor->id)->where('status', 'Completed')->count();
            $overdueProjects = Project::where('supervisor_id', $profile->supervisor->id)->where('endDate','<', Carbon::now())->count();
            $notStartedProjects = Project::where('supervisor_id', $profile->supervisor->id)->where('status', 'Not Started')->count();

            $tasks = [];
            foreach($allProjects as $project){
              $tasks=  array_merge($tasks, reafctorDashboardTasks($project->tasks));
            }
            return compact('totalProjects','completedProjects','notStartedProjects','overdueProjects','tasks');
        } 
    }
    
}