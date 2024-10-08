<?php
namespace App\Traits;
use App\Models\Profile;
use App\Models\Session;
use Illuminate\Support\Facades\Cookie;
trait Refactor
{
    public function refactorProfile($profile){
        $files = $this->getElementFiles($profile);
        if ($profile->hasRole('user')){
            $user = $profile->user;
            $applicationsData = $user->applications;
            $applications = [];          
            foreach($applicationsData as $application){
                $applications[]=['id'=>$application->id,"offer_id"=>$application->offer_id,'status'=>$application->status];
            } 
            $refactored = [
                "id"=>$user->id,
                "profile_id"=>$profile->id,
                "firstName"=>$profile->firstName,
                "lastName"=>$profile->lastName,
                'created_at'=>$profile->created_at->format('Y-m-d H:i:s'),
                'updated_at'=>$profile->updated_at->format('Y-m-d H:i:s'),
                "gender"=>$profile->gender,
                "email"=>$profile->email,
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "academicLevel" => $user->academicLevel,
                "establishment" => $user->establishment,
                "applications"=>$applications,
                "files"=>$files
            ];
        return $refactored;
            };
        if (in_array($profile->getRoleNames()[0],['admin','super-admin']) ){
            $admin = $profile->admin;
            $refactored = [
                "id"=>$admin->id,
                "profile_id"=>$profile->id,
                "firstName"=>$profile->firstName,
                "lastName"=>$profile->lastName,
                "email"=>$profile->email,
                "gender"=>$profile->gender,
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "files"=>$files??[],
            ];
            return $refactored;
            } ;
        if ($profile->hasRole('supervisor')){
            $supervisor = $profile->supervisor;
            $projectsData = $supervisor->projects;
            $projects = [];
            foreach($projectsData as $project){
                $projects[]=["id" => $project->id,"status"=>$project->status];
            }
            $refactored = [
                "id"=>$supervisor->id,
                "profile_id"=>$profile->id,
                "firstName"=>$profile->firstName,
                "lastName"=>$profile->lastName,
                "email"=>$profile->email,
                "gender"=>$profile->gender,
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "projects"=>$projects,
                "files"=>$files??[]
            ];
            return $refactored;
            };
        if($profile->hasRole('intern')){
            $intern = $profile->intern;
            $projectsData = $intern->projects;
            $projects = [];
            foreach($projectsData as $project){
                $projects[]=["id" => $project->id,"status"=>$project->status];
            }
            $refactored = [
                "id"=>$intern->id,
                "profile_id"=>$profile->id,
                "firstName"=>$profile->firstName,
                "lastName"=>$profile->lastName,
                "gender"=>$profile->gender,
                "email"=>$profile->email,
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "projects"=>$projects,
                "academicLevel" => $intern->academicLevel,
                "establishment" => $intern->establishment,
                "projectLink" => $intern->projectLink,
                "startDate" => $intern->startDate,
                "specialty" => $intern->specialty,
                "endDate" => $intern->endDate,
                "files"=>$files??[],
                "tasks"=>$intern->tasks()->count()
            ];
            return $refactored;
        }
    }
    public function refactoProject($project){
        $supervisor = $project->supervisor;
        $projectManager = $project->projectManager;
        $teamMembersData = $project->interns;
        $teamMembers=[];
        foreach($teamMembersData as $teamMember){
            array_push($teamMembers, $teamMember->id);
          }
        $tasksData = $project->tasks;
        $tasks = [];
        foreach($tasksData as $task){
            array_push($tasks,$this->refactorTask($task));
            }
        return [
            'id'=>$project->id,
            'subject'=>$project->subject,
            "startDate"=>$project->startDate,
            "endDate"=>$project->endDate,
            "created_at"=>$project->created_at->format('Y-m-d H:i:s'),
            "updated_at"=>$project->updated_at->format('Y-m-d H:i:s'),
            "status"=>$project->status,
            "priority"=>$project->priority,
            'description'=>$project->description,
            'projectManager'=>!$projectManager?null:$projectManager->id,
            'supervisor' => $supervisor->id??null,
            'teamMembers'=>$teamMembers,'tasks'=>$tasks];
    }
    public function refactorTask($task){
            $intern = $task->intern;
            if(!$intern){
                $intern = 'None';
            }else{
                $profile = $intern->profile;
                $intern = [
                    "id" => $intern->id,
                    "profile_id" => $profile->id,
                    "firstName" => $profile->firstName,
                    "lastName" => $profile->lastName,
                    "gender" => $profile->gender,
                    "email" => $profile->email,
                    "files"=>$this->getElementFiles($profile),
                ];
            }
            return [
                    "id"=> $task->id,
                    "project"=> $task->project_id,
                    "title"=>$task->title,
                    'description'=>$task->description,
                    'dueDate'=>$task->dueDate,
                    'priority'=>$task->priority,
                    'status'=>$task->status,
                    'created_at'=>$task->created_at->format('Y-m-d H:i:s'),
                    'updated_at'=>$task->updated_at->format('Y-m-d H:i:s'),
                    'assignee'=>$intern
                ];
    }
    public function refactorOffer($offer){
        $applicationsData = $offer->applications;
        $applications = [];
        foreach($applicationsData as $application){
            array_push($applications,['id'=>$application->id,'status'=>$application->status,"updated_at"=>$application->updated_at->format('Y-m-d H:i:s')]);
        }
        return [
            "id"=> $offer->id,
            "title"=>$offer->title,
            'description'=>$offer->description,
            "sector"=> $offer->sector,
            'experience'=>$offer->experience,
            'skills'=>$offer->skills,
            'company'=>$offer->company,
            'duration'=>$offer->duration,
            'type'=>$offer->type,
            'visibility'=>$offer->visibility,
            'status'=>$offer->status,
            'city'=>$offer->city,
            'publicationDate'=>$offer->created_at->format('Y-m-d H:i:s'),
            'created_at'=>$offer->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$offer->updated_at->format('Y-m-d H:i:s'),
            'applications'=>$applications
            ];
    }
    public function refactorApplication($application){
        $offerData = $application->offer;
        if ( $application->intern){
            $profile = $application->intern->profile;
        }else{
            $profile = $application->user->profile;
        }
        $offer = $this->refactorOffer($offerData);
        return [
            "id"=> $application->id,
            "startDate"=>$application->startDate,
            "endDate"=>$application->endDate,
            "motivationLetter"=>$application->motivationLetter,
            "status"=>$application->status,
            "owner"=> $this->refactorProfile($profile),
            "offer"=> $offer,
            "files"=>$this->getElementFiles($application),
            'created_at'=>$application->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$application->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    public function refactorSettings($setting){
        $files = $this->getElementFiles($setting);
        return [
           "appName"=> $setting->appName, 
           "companyName"=> $setting->companyName, 
           'email'=> $setting->email ,
            "phone"=>$setting->phone ,
           "facebook"=> $setting->facebook ,
            "instagram"=>$setting->instagram,
           "twitter"=> $setting->twitter,
           "youtube"=> $setting->youtube,
           "linkedin"=> $setting->linkedin,
           "maps"=> $setting->maps,
            "location"=>$setting->location,
            "aboutDescription"=>$setting->aboutDescription,
            "files"=>$files
        ];
    }
    public function refactorSession($session){
        $profile = Profile::find($session->profile_id);
        $currentSession = Session::where('token', Cookie::get('token'))->first();
        $allActivities = $session->activities;
        foreach($allActivities as $actevitie){
           $activities[]=$this->refactorActivity($actevitie);
        }
        if($currentSession&&$currentSession->id ===$session->id){
            $isCurrent = 'true';
        } else{
            $isCurrent= 'false';
        }
        return [
            'id'=>$session->id,
            'fullName'=>$profile->firstName.' '.$profile->lastName ,
            'email'=>$profile->email ,    
            'ip'=>$session->ip,
            'browser'=>$session->browser,
            'device'=>$session->device,
            'status'=>$session->status,
            'isCurrent'=>$isCurrent,
            'location'=>$session->location,
            'activities'=>$activities??[],
            'created_at'=>$session->created_at,
            'updated_at'=>$session->updated_at,
        ];
    }
    public function refactorActivity($activitie){   
        $profile= $activitie->profile; 
        return [
            'id' => $activitie->id,
            'initiator' => $profile->gender .'. '.$profile->firstName.' '.$profile->lastName,
            'model' => $activitie->model,
            'action' => $activitie->action,
            'activity' => $activitie->activity,
            'object' => $activitie->object,
            'created_at' => $activitie->created_at,
        ];
    }
    public function refactorNotification($notification){
        return [
            'id'=>$notification->id,
            'activity'=>$notification->activity,
            'action'=>$notification->action,
            'object'=>$notification->object,
            'isRead'=>$notification->isRead,
            'created_at'=>$notification->created_at,
        ];
    }
}