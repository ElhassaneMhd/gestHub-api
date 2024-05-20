<?php

namespace App\Traits;
use App\Models\Project;
use App\Models\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


trait Update
{
    use Refactor;
    public function updateProfile($data,$profile){
        $validatedData = $data->validate([
            'email' => 'email',
            'firstName' =>'string',
            'lastName' =>'string',
            'phone' =>'string',
            'gender' =>'string|in:M,Mme',
            'password' => [
                'string',
                Password::min(8)->numbers(),
                'confirmed',
            ]
        ]); 
        if ($profile->email!==$data['email']){
            $validatedData = $data->validate([
                'email' => 'email|unique:profiles,email',
                'firstName' =>'string',
                'lastName' =>'string',
                'phone' =>'string',
                'gender' =>'string|in:M,Mme',
                'password' => [
                    'string',
                    Password::min(8)->numbers(),
                    'confirmed',
                ],                
            ]);
        }   
        DB::beginTransaction();
        $profile->update($validatedData);
        $isCommited = true;
        $otherData = array_filter([
            'academicLevel' => $data['academicLevel'] ?? null,
            'establishment' => $data['establishment'] ?? null,
            'startDate' => $data['startDate'] ?? null,
            'specialty' => $data['specialty'] ?? null,
            'endDate' => $data['endDate'] ?? null,
            'projectLink' => $data['projectLink'] ?? null,
        ]);
        if ($profile->getRoleNames()[0]=='user') {
            $user = $profile->user;
            $isCommited=$user->update($otherData);
        }
        if ($profile->getRoleNames()[0]=='intern') {
            $intern = $profile->intern;
            $isCommited=$intern->update($otherData);
        }   
        if($isCommited){
            DB::commit();
            return response()->json($this->refactorProfile($profile));
        }else{
            DB::rollBack();
            return [];
        }
    }
    public function updateProfilePassword($request,$profile){
        $validatedData = $request->validate([
                    'currentPassword' => [
                            'required',
                            Password::min(8)->numbers(),
                        ]  ,
                    'password' => [
                            'string',
                            'required',
                            Password::min(8)->numbers(),
                            'confirmed',
                        ]   
                    ]);
    if (Hash::check($validatedData['currentPassword'], $profile->password)) {
        if (Hash::check($validatedData['password'], $profile->password)) {
                return response()->json(['message' => 'Please enter a new password '], 400); 
            }
        $hashedPassword = Hash::make($validatedData['password']);
        $profile->password = $hashedPassword;
        $profile->save();
        return response()->json(['message' => ' Password updated successfully'], 200); 
    }
    return response()->json(['message' => 'Incorrect current password'], 400);
    }
    public function updateProject($data,$project){
        $tasks=$project->tasks;
        $validatedProject = $data->validate([
            'subject' => 'string',
            'description' => 'string',
            'startDate' => 'date',
            'endDate' => 'date',
            'status' => 'string',
            'priority' => 'in:Low,Medium,High,None',
            'supervisor_id' => 'exists:supervisors,id',
            'intern_id' => 'nullable|exists:interns,id',
            'teamMembers.*' => 'exists:interns,id',
        ]);
        $project->update($validatedProject);
        if ($data->has('teamMembers')){
            foreach($tasks as $task){
                if(!in_array($task->intern_id ,$data['teamMembers'])){
                    $task->intern_id = null;
                    $task->save();
                }  
            }
            $project->interns()->detach();
            $project->interns()->attach($data['teamMembers']);
        }
        return $project;
    }
    public function updateTask($request,$task){
        $validatedData = $request->validate([
        'title' => 'nullable|max:255',
        'description' => 'nullable|string',
        'dueDate' => 'nullable|date',
        'priority' => 'in:Low,Medium,High,None',
        'status' => 'in:To Do,Done,In Progress',
        'intern_id' => 'nullable|exists:interns,id',
        'project_id' => 'exists:projects,id',
    ]);
        $task->update($validatedData);
        $this->updateProjectStatus($task->project_id);
        return $task;
    }
    public function updateProjectStatus($project_id){
        $project = Project::find($project_id);
        $todoCount = $project->tasks()->where('status', 'To Do')->count();
        $progressCount = $project->tasks()->where('status', 'In Progress')->count();
        $doneCount = $project->tasks()->where('status', 'Done')->count();

        if ($doneCount > 0 && $todoCount == 0 && $progressCount == 0) {
            $project->status = "Completed";
        } elseif ($progressCount > 0 || $doneCount > 0) {
            $project->status = "In Progress";
        } else {
            $project->status = "Not Started";
        }

        $project->save();
    }
    public function updateOffer($request,$offer){
           $updateData = array_filter([
                "title"=>   $request['title'] ?? null,
                "description"=>   $request['description'] ?? null,
                'sector'=> $request['sector'] ?? null,
                'experience'=> $request['experience'] ?? null,
                'skills'=>  $request['skills'] ?? null,
                'duration'=>  $request['duration'] ?? null,
                'direction'=>  $request['direction'] ?? null,
                'visibility'=>  $request['visibility'] ?? null,
                'status'=> $request['status'] ?? null,
                'city'=>  $request['city'] ?? null,
                'type'=> $request['type'] ?? null,
            ]);
        $offer->update($updateData);
        return $offer;
    }
    public function updateApplication($request,$application){
         $updateData = array_filter([
                "user_id"=>   $request['user_id'] ?? null,
                "offer_id"=>   $request['offer_id'] ?? null,
                'startDate'=> $request['startDate'] ?? null,
                'endDate'=> $request['endDate'] ?? null,
            ]);
        $application->update($updateData);
        return $application;
    }
    public function processApplication($application,$traitement){
        if ($application->status !== 'Pending'){
            return response()->json(['message' => 'application alraedy processed'], 404);
        }
        if($traitement==='approve'){
            $application->status = 'Approved';
            $application->isRead = 'false';
            $application->save();
            return response()->json(['message' => 'application approved succeffully'], 200);
        }
        if($traitement==='reject'){
            $application->status='Rejected';
            $application->save();
            return response()->json(['message' => 'application rejected succeffully'], 200);
        }
    }
    public function updateSession($session){
        $session->status = 'Offline';
        $session->save();
    }
}