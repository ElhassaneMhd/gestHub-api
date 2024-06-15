<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\Application;
use App\Models\Email;
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
use Schema;

trait Get
{
    public function GetAll($data)
    {
        $profile = Auth::user();
        $all = [];
        if ($data === 'admins') {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                $profile = $admin->profile;
                if ($profile->getRoleNames()[0] !== 'super-admin') {
                    $all[] = $this->refactorProfile($profile);
                }
            }
        } elseif ($data === 'supervisors') {
            $supervisors = Supervisor::all();
            foreach ($supervisors as $supervisor) {
                $profile = $supervisor->profile;
                $all[] = $this->refactorProfile($profile);
            }
        } elseif ($data === 'interns') {
            $interns = Intern::all();
            foreach ($interns as $intern) {
                $profile = $intern->profile;
                $all[] = $this->refactorProfile($profile);
            }
        } elseif ($data === 'users') {
            $users = User::all();
            foreach ($users as $user) {
                $profile = $user->profile;
                $all[] = $this->refactorProfile($profile);
            }
        } elseif ($data === 'projects') {
            if (Auth::user()->hasRole('supervisor')) {
                $projects  = $profile->supervisor->projects;
            }
            if (Auth::user()->hasRole('intern')) {
                $projects  = $profile->intern->projects;
            }
            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) {
                $projects = Project::all();
            }
            foreach ($projects ?? [] as $project) {
                $all[] = $this->refactoProject($project);
            }
        } elseif ($data === 'offers') {
            $offers = Offer::all();
            foreach ($offers as $offer) {
                $all[] = $this->refactorOffer($offer);
            }
        } elseif ($data === 'applications') {
            if (Auth::user()->hasRole('user')) {
                $user = $profile->user;
                $applications = $user->applications;
            }
            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) {
                $applications = Application::all();
            }
            foreach ($applications as $application) {
                $all[] = $this->refactorApplication($application);
            }
        } elseif ($data === 'settings') {
            $settings = Setting::first();
            if ($settings) {
                $all = $this->refactorSettings($settings);
            }
        } elseif ($data === 'sessions') {
            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) {
                $sessions = Session::all();
            } else {
                $sessions = $profile->sessions;
            }
            foreach ($sessions as $session) {
                $all[] = $this->refactorSession($session);
            }
        } elseif ($data === 'notifications') {
            $profile = auth()->user();
            $notifications = $profile->notifications;
            foreach ($notifications as $notification) {
                $allNotifications[] = $this->refactorNotification($notification);
            }
            return $allNotifications ?? [];
        } elseif ($data === 'emails') {
            (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) ? $emails = Email::all() : $emails = [];
            $all = $emails;
        }
        if (!isset($all)) {
            return response()->json($all);
        } else {
            return response()->json($all, 200);
        }
    }
    public function GetByDataId($data, $id)
    {
        if ($data === 'admins') {
            $admin = Admin::Find($id);
            if ($admin) {
                $profile = $admin->profile;
                $results = $this->refactorProfile($profile);
            }
        } elseif ($data === 'interns') {
            $intern = Intern::Find($id);
            if ($intern) {
                $profile = $intern->profile;
                $results = $this->refactorProfile($profile);
            }
        } elseif ($data === 'supervisors') {
            $ssupervisor = Supervisor::Find($id);
            if ($ssupervisor) {
                $profile = $ssupervisor->profile;
                $results = $this->refactorProfile($profile);
            }
        } elseif ($data === 'users') {
            $user = User::Find($id);
            if ($user) {
                $profile = $user->profile;
                $results = $this->refactorProfile($profile);
            }
        } elseif ($data === 'profiles') {
            $profile = Profile::Find($id);
            if ($profile) {
                $results = $this->refactorProfile($profile);
            }
        } elseif ($data === 'projects') {
            $project = Project::Find($id);
            if ($project) {
                $results = $this->refactoProject($project);
            }
        } elseif ($data === 'tasks') {
            $task = Task::Find($id);
            if ($task) {
                $results = $this->refactorTask($task);
            }
        } elseif ($data === 'applications') {
            $application = Application::find($id);
            if ($application) {
                $results = $this->refactorApplication($application);
            }
        } elseif ($data === 'offers') {
            $offer = Offer::Find($id);
            if ($offer) {
                $results = $this->refactorOffer($offer);
            }
        } elseif ($data === 'sessions') {
            $session = Session::Find($id);
            if ($session) {
                $results = $this->refactorSession($session);
            }
        } elseif ($data === 'notifications') {
            $notification = Notification::Find($id);
            if ($notification) {
                $results = $this->refactorNotification($notification);
            }
        } elseif ($data === 'emails') {
            (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) && $email = Email::Find($id);
            $results = $email ?? null;
        } else {
            return response()->json(['message' => 'Looking for undefined api'], 404);
        }
        if (empty($results)) {
            return response()->json(['message' => 'Looking for undefined data, try with a different id'], 404);
        }
        return response()->json($results);
    }
    public function getAllAcceptedUsers()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (count($user->applications->where('status', '=', 'Approved')) > 0) {
                $profile = $user->profile;
                $accptedUsers[] = $this->refactorProfile($profile);
            }
        }
        return response()->json($accptedUsers ?? [], 200);
    }
    public function getElementFiles($element)
    {
        if ($element) {
            $files = $element->files;
            foreach ($files as $file) {
                $Allfiles[] = ['url' => $file->url, 'type' => $file->type];
            }
        }
        return $Allfiles ?? [];
    }
    public function getAllStats()
    {
        $profile = Auth::user();
        function getLengthElement($element)
        {
            return DB::table($element)->count();
        }
        function reafctorDashboardTasks($allTasks)
        {
            foreach ($allTasks as $task) {
                $tasks[] = ['created_at' => $task->created_at, 'updated_at' => $task->updated_at, 'status' => $task->status, 'dueDate' => $task->dueDate];
            }
            return $tasks ?? [];
        }
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) {

            $admins = getLengthElement('admins');
            $interns = getLengthElement('interns');
            $supervisors = getLengthElement('supervisors');
            $personnel = compact('admins', 'interns', 'supervisors');

            $totalApplications = getLengthElement('applications');
            $pendingApplications = Application::where('status', 'Pending')->count();
            $approvedApplications = Application::where('status', 'Approved')->count();
            $rejectedApplications = Application::where('status', 'Rejected')->count();
            $applications = compact('totalApplications', 'pendingApplications', 'approvedApplications', 'rejectedApplications');

            $totalOffers = getLengthElement('offers');

            $latestOffers = Offer::withCount('applications')->get()->sortBy('applications_count', SORT_REGULAR, true)->take(7)->map(function ($offer) {
                $approved = 0;
                $rejected = 0;
                foreach ($offer->applications as $application) {
                    $application->status === 'Approved' && $approved += 1;
                    $application->status === 'Rejected' && $rejected += 1;
                }
                return [
                    'name' => $offer->title,
                    'applications' => compact('approved', 'rejected')
                ];
            })->values();
            $offers = compact('totalOffers', 'latestOffers');

            $allTasks = Task::all();
            $totalTasks = getLengthElement('tasks');
            $toDoTasks = Task::where('status', 'To Do')->count();
            $inProgressTasks = Task::where('status', 'In Progress')->count();
            $completedTasks = Task::where('status', 'Done')->count();
            $overdueTasks = Task::where('dueDate', '<', Carbon::now())->count();
            $tasks = reafctorDashboardTasks($allTasks);
            $tasks = compact('tasks', 'totalTasks', 'toDoTasks', 'inProgressTasks', 'completedTasks', 'overdueTasks');

            $totalProjects = getLengthElement('projects');
            $completedProjects = Project::where('status', 'Completed')->count();
            $inProgressProjects = Project::where('status', 'In Progress')->count();
            $notStartedProjects = Project::where('status', 'Not Started')->count();
            $overdueProjects = Project::where('endDate', '<', Carbon::now())->count();
            $projects = compact('totalProjects', 'completedProjects', 'notStartedProjects', 'inProgressProjects', 'overdueProjects');

            return compact('personnel', 'projects', 'offers', 'applications', 'tasks');
        }
        if (Auth::user()->hasRole('intern')) {
            $allTasks = Auth::user()->intern->tasks;
            $totalTasks = Auth::user()->intern->tasks()->count();
            $toDoTasks = Task::where('intern_id', $profile->intern->id)->where('status', 'To Do')->count();
            $completedTasks = Task::where('intern_id', $profile->intern->id)->where('status', 'Done')->count();
            $inProgressTasks = Task::where('intern_id', $profile->intern->id)->where('status', 'In Progress')->count();
            $overdueTasks = Task::where('intern_id', $profile->intern->id)->where('dueDate', '<', Carbon::now())->count();
            $totalProjects = Auth::user()->intern->projects->count();
            $tasks = reafctorDashboardTasks($allTasks);
            $tasks = compact('totalTasks', 'toDoTasks', 'inProgressTasks', "completedTasks", 'overdueTasks', 'tasks');
            $projects = compact('totalProjects');
            return compact('projects', 'tasks');
        }
        if (Auth::user()->hasRole('supervisor')) {
            $totalProjects = Auth::user()->supervisor->projects()->count();
            $allProjects = Auth::user()->supervisor->projects;
            $completedProjects = Project::where('supervisor_id', $profile->supervisor->id)->where('status', 'Completed')->count();
            $overdueProjects = Project::where('supervisor_id', $profile->supervisor->id)->where('endDate', '<', Carbon::now())->count();
            $notStartedProjects = Project::where('supervisor_id', $profile->supervisor->id)->where('status', 'Not Started')->count();
            $inProgressProjects = Project::where('status', 'In Progress')->count();

            $tasks = [];
            $toDoTasks = 0;
            $overdueTasks = 0;
            $inProgressTasks = 0;
            $completedTasks = 0;
            foreach ($allProjects as $project) {
                $tasks =  array_merge($tasks, reafctorDashboardTasks($project->tasks));
            }
            $totalTasks = count($tasks);
            foreach ($tasks as $task) {
                ($task['status'] === 'To Do') && $toDoTasks += 1;
                ($task['status'] === 'Done') && $completedTasks += 1;
                ($task['status'] === 'In Progress') && $inProgressTasks += 1;
                ($task['dueDate'] < Carbon::now()) && $overdueTasks += 1;
            }
            $tasks = compact('totalTasks', 'toDoTasks', 'inProgressTasks', 'completedTasks', 'overdueTasks', 'tasks');
            $projects = compact('totalProjects', 'completedProjects', 'notStartedProjects', 'inProgressProjects', 'overdueProjects');

            return compact('projects', 'tasks');
        }
    }
    public function getAllCount()
    {
        $profile = Auth::user();

        $admins = Admin::all()->count();
        $interns = Intern::all()->count();
        $supervisors = Supervisor::all()->count();
        $users = User::all()->count();
        $applications = Application::all()->count();
        $offers = Offer::all()->count();
        $projects = Project::all()->count();
        $sessions = Session::all()->count();
        $emails = Email::all()->count();
        if ($profile->hasRole('admin') || $profile->hasRole('super-admin')) {
            return compact('admins', 'interns', 'supervisors', 'users', 'applications', 'offers', 'projects', 'sessions', 'emails');
        }
        if ($profile->hasRole('supervisor') || $profile->hasRole('intern')) {
            $sessions = $profile->sessions->count();
            $projects = $profile->hasRole('supervisor') ? $profile->supervisor->projects->count() : $profile->intern->projects->count();
            return compact('projects', 'sessions');
        }
    }
}
