<?php
namespace App\Traits;
trait Refactor
{
    public function refactorProfile($profile){
        $files = $this->getElementFiles($profile);
        if ($profile->getRoleNames()[0]==='user'){
            $user = $profile->user;
            $demandsData = $user->demands;
            $demands = [];          
            foreach($demandsData as $demand){
                $demands[]=['id'=>$demand->id,"offer_id"=>$demand->offer_id];
            } 
            $refactored = [
                "id"=>$user->id,
                "profile_id"=>$profile->id,
                "firstName"=>$profile->firstName,
                "lastName"=>$profile->lastName,
                'created_at'=>$profile->created_at->format('Y-m-d H:i:s'),
                'updated_at'=>$profile->updated_at->format('Y-m-d H:i:s'),
                "gender"=>$user->gender,
                "email"=>$profile->email,
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "academicLevel" => $user->academicLevel,
                "establishment" => $user->establishment,
                "demands"=>$demands,
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
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "files"=>$files??[],
            ];
            return $refactored;
            } ;
        if ($profile->getRoleNames()[0]==='supervisor'){
            $supervisor = $profile->supervisor;
            $projectsData = $supervisor->projects;
            $projects = [];
            foreach($projectsData as $project){
                array_push($projects, $project->id);
            }
            $refactored = [
                "id"=>$supervisor->id,
                "profile_id"=>$profile->id,
                "firstName"=>$profile->firstName,
                "lastName"=>$profile->lastName,
                "email"=>$profile->email,
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "projects"=>$projects,
                "files"=>$files??[]
            ];
            return $refactored;
            };
        if($profile->getRoleNames()[0]==='intern'){
            $intern = $profile->intern;
            $projectsData = $intern->projects;
            $projects = [];
            foreach($projectsData as $project){
                $projects[]=$project->id;
            }
            $tasksData = $intern->tasks;
            $tasks = [];
            foreach($tasksData as $task){
                $tasks[]=$this->refactorTask($task);
            }
            $refactored = [
                "id"=>$intern->id,
                "profile_id"=>$profile->id,
                "firstName"=>$profile->firstName,
                "lastName"=>$profile->lastName,
                "gender"=>$intern->gender,
                "email"=>$profile->email,
                "phone"=>$profile->phone,
                "role"=>$profile->getRoleNames()[0],
                "projects"=>$projects,
                "academicLevel" => $intern->academicLevel,
                "establishment" => $intern->establishment,
                "startDate" => $intern->startDate,
                "specialty" => $intern->specialty,
                "endDate" => $intern->endDate,
                "files"=>$files??[],
                "tasks"=>$tasks,
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
            'supervisor' => $supervisor->id,
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
                    "email" => $profile->email
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
        $demandsData = $offer->demands;
        $demands = [];
        foreach($demandsData as $demand){
            array_push($demands,['id'=>$demand->id,'status'=>$demand->status,"updated_at"=>$demand->updated_at->format('Y-m-d H:i:s')]);
        }
        return [
            "id"=> $offer->id,
            "title"=>$offer->title,
            'description'=>$offer->description,
            "sector"=> $offer->sector,
            'experience'=>$offer->experience,
            'skills'=>$offer->skills,
            'direction'=>$offer->direction,
            'duration'=>$offer->duration,
            'type'=>$offer->type,
            'visibility'=>$offer->visibility,
            'status'=>$offer->status,
            'city'=>$offer->city,
            'publicationDate'=>$offer->created_at->format('Y-m-d H:i:s'),
            'created_at'=>$offer->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$offer->updated_at->format('Y-m-d H:i:s'),
            'demands'=>$demands
            ];
    }
    public function refactorDemand($demand){
        $offerData = $demand->offer;
        if ( $demand->intern){
            $profile = $demand->intern->profile;
        }else{
            $profile = $demand->user->profile;
        }
        $offer = $this->refactorOffer($offerData);
        return [
            "id"=> $demand->id,
            "startDate"=>$demand->startDate,
            "endDate"=>$demand->endDate,
            "motivationLetter"=>$demand->motivationLetter,
            "status"=>$demand->status,
            "isRead"=> $demand->isRead,
            "owner"=> $this->refactorProfile($profile),
            "offer"=> $offer,
            "files"=>$this->getElementFiles($demand),
            'created_at'=>$demand->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$demand->updated_at->format('Y-m-d H:i:s'),
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
    public function getElementFiles($element){
        if ($element){
            $files = $element->files;
            foreach($files as $file){
                $Allfiles[] = ['url' => asset($file->url),'type'=>$file->type];
            }
        }
        return $Allfiles??[];
    }
}