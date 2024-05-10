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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

trait Store
{
    use Update,Delete;
    public function storeProfile($request) {
        $validatedProfile = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required|string|unique:profiles,phone|max:255',
            'email' => 'required|email|unique:profiles,email|max:255',
            'password' => [
                'required',
                'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                'confirmed',
            ],
            'role'=>'required|in:admin,supervisor,intern'
        ]);
        DB::beginTransaction();
            $profile = new Profile;
            $profile->firstName = $validatedProfile['firstName'];
            $profile->lastName = $validatedProfile['lastName'];
            $profile->email = $validatedProfile['email'];
            $profile->phone = $validatedProfile['phone'];
            $profile->password = bcrypt($validatedProfile['password']);
            $profile->assignRole($request->role) ;
            $profile->save();
            $isCommited = false;
        if ($validatedProfile['admin']='admin' ) {
           if( Auth::user()->hasRole('super-admin')){
               $admin = new Admin;
               $admin->profile_id = $profile->id;
               $isCommited=$admin->save();
            }else{
                return response()->json(['error' => "You can't process this action "], 403);
            }
        }
        if ($request->role == 'supervisor') {
            $supervisor = new Supervisor;
            $supervisor->profile_id = $profile->id;
            $isCommited=$supervisor->save();
        }
        if ($validatedProfile['role']== 'intern') {
                $validatedIntern = $request->validate([
                'academicLevel' => 'required|string',
                'establishment' => 'required|string',
                'gender' => 'required|in:M,Mme',
                'specialty' => 'string',
                'startDate' => 'required',
                'endDate' => 'required',
            ]);
            $intern = new Intern;
            $intern->profile_id = $profile->id;
            $intern->academicLevel = $validatedIntern['academicLevel'];
            $intern->establishment = $validatedIntern['establishment'];
            $intern->gender = $validatedIntern['gender'];
            $intern->specialty = $validatedIntern['specialty'];
            $intern->startDate = $validatedIntern['startDate'];
            $intern->endDate = $validatedIntern['endDate'];
            $isCommited=$intern->save();
        }
        if($isCommited){
            DB::commit();
            return response()->json($this->refactorProfile($profile),200)  ;
        }else{
            DB::rollBack();
            return response()->json(['message'=>'cannot store this :'.$request->role] ,404)  ;
        }
    }
    public function storeUser($request){
         $validatedData = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email|max:255',
            'password' => [
                'required',
                'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                'confirmed',
            ],
            'academicLevel' => 'required|string',
            'gender' => 'required|in:M,Mme',
            'establishment' => 'required|string'
                    ]);
         DB::beginTransaction();                  
            $profile = new Profile;
            $profile->firstName = $validatedData['firstName'];
            $profile->lastName = $validatedData['lastName'];
            $profile->email = $validatedData['email'];
            $profile->phone = $validatedData['phone'];
            $profile->password = bcrypt($validatedData['password']);
            $profile->assignRole('user');
            $profile->save();
           
            $user = new User;
            $user->profile_id = $profile->id;
            $user->academicLevel = $validatedData['academicLevel'];
            $user->establishment = $validatedData['establishment'];
            $user->gender = $validatedData['gender'];
            $isCommited =$user->save();
             if($isCommited){
                 DB::commit();
                 return $profile;
            }else{
                DB::rollBack();
                return [];
            }
    }
    public function storeProject($request){
        $validatedProject = $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'status' => 'required|in:Not Started,Completed,In Progress',
            'priority' => 'required|in:Low,Medium,High,None',
            'supervisor_id' => 'required|exists:supervisors,id',
            'intern_id' => 'nullable|exists:interns,id',
            'tasks' => 'array',
            'teamMembers' => 'array|exists:interns,id',
        ]);
            $project = new Project;
            $project->subject = $validatedProject['subject'];
            $project->description = $validatedProject['description'];
            $project->startDate = $validatedProject['startDate'];
            $project->endDate = $validatedProject['endDate'];
            $project->status = $validatedProject['status'];
            $project->priority = $validatedProject['priority'];
            $project->supervisor_id = $validatedProject['supervisor_id'];
            $project->intern_id = $validatedProject['intern_id']; 
            $project->save();
        foreach ($validatedProject['teamMembers'] as $teamMemberId) {
            $project->interns()->attach($teamMemberId);
        }
        foreach ($request->tasks  as $taskData) {
            $task = new Task;
            $task->title = $taskData['title'];
            $task->description = $taskData['description'];
            $task->dueDate = $taskData['dueDate'];
            $task->priority = $taskData['priority'];
            $task->status = $taskData['status'];
            $task->intern_id = $taskData['intern_id']; 
            $task->project_id = $project->id; 
            $task->save();
        }
        return $project;
    }
    public function storeTask($request){
        $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'nullable|string',
        'dueDate' => 'nullable|date',
        'priority' => 'required|in:Low,Medium,High,None',
        'status' => 'required|in:To Do,Done,In Progress',
        'intern_id' => 'nullable|exists:interns,id',
        'project_id' => 'required|exists:projects,id',
    ]);
        $task = new Task;
        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->dueDate = $validatedData['dueDate'];
        $task->priority = $validatedData['priority'];
        $task->status = $validatedData['status'];
        $task->intern_id = $validatedData['intern_id']; 
        $task->project_id = $validatedData['project_id']; 
        $task->save();
        $this->updateProjectStatus($task->project_id);
        return $task;
    }
    public function storeOffer($request){
    // Validate the incoming request data
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'sector' => 'required|max:255',
        'experience' => 'required|in:Expert,Intermediate,Beginner',
        'skills' => 'nullable',
        'duration' => 'numeric|min:1|max:24',
        'direction' => 'required|string',
        'visibility'=>'required|in:Visible,Hidden',
        'status'=>'required|in:Normal,Urgent',
        'city'=>'required|string',
        'type'=>'required|in:Remote,Onsite,Hybrid',
    ]);
    // Create a new offer with the validated data
        $offer = new Offer;
        $offer->title = $validatedData['title'];
        $offer->description = $validatedData['description'];
        $offer->sector = $validatedData['sector'];
        $offer->experience = $validatedData['experience'];
        $offer->skills = $validatedData['skills'];
        $offer->duration = $validatedData['duration'];
        $offer->direction = $validatedData['direction'];
        $offer->visibility = $validatedData['visibility'];
        $offer->status = $validatedData['status'];
        $offer->city = $validatedData['city'];
        $offer->type = $validatedData['type'];
        $offer->save();

        return $offer;
    }
    public function storeApplication($request){
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'user_id' => 'required|exists:users,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'motivationLetter' => 'required|string',
            'cv' => 'nullable|file'
        ]);
        $applicatione = new Application;
        $applicatione->offer_id = $request->input('offer_id');
        $applicatione->user_id = $request->input('user_id');
        $applicatione->startDate = $request->input('startDate');
        $applicatione->endDate =  $request->input('endDate');
        $applicatione->motivationLetter =  $request->input('motivationLetter');
        $applicatione->save();
        $profile=$applicatione->user->profile;
        if ($request->hasFile('cv')&&$profile) {
            $this->storeOneFile($request, $profile, 'cv');            
        }
        if ($request->hasFile('applicationeStage')&&$applicatione) {
            $this->storeOneFile($request, $applicatione, 'applicationeStage');            
        }
        return response()->json($this->refactorApplication($applicatione));
    }
    public function storInternFromUser($user){
        $application =$user->applications->where('status','=','Approved')->first();
        if(!$application){
            return response()->json(['message' => 'this user has no aprouved application'], 404);
        }
        $offer = $application->offer;
        $profile = $user->profile;
        $applications = $user->applications;

        DB::beginTransaction();
        $intern = new Intern;
        $intern->profile_id = $profile->id;
        $intern->academicLevel = $user['academicLevel'];
        $intern->establishment = $user['establishment'];
        $intern->gender = $user['gender'];
        $intern->endDate = $application['endDate'];
        $intern->startDate = $application['startDate'];
        $intern->specialty = $offer['sector'];

        $isCommited[]=$user->delete();
        $profile->removeRole('user'); 
        $profile->assignRole('intern');
        $isCommited[] = $profile->hasRole('intern');
        $isCommited[]=$intern->save();
        $isCommited[]=$application->intern_id = $intern->id;
        $application -> save();
        foreach($applications as $otherApplication){
            if($otherApplication->id !==$application->id ){
                $isCommited[]=$otherApplication->delete();
            }
        } 
        if(in_array(false||null,$isCommited)){
            DB::rollBack();
            return response()->json(['message'=>'error , save this intern'],400) ;
        }else{
            DB::commit();
        } 
    }   
    public function storeOneFile($request,$element,$fileType){
          $files = $request->file($fileType);
          $name =$files->getClientOriginalName();
          $unique = uniqid();
        if (in_array($fileType ,['avatar',"appLogo"]) ) {
            $request->validate([
                $fileType => 'file|mimes:jpg,JPG,jpeg,JPEG,,PNG,png,svg,SVG|max:5120',
            ]);
        } else {
            $request->validate([
                $fileType => 'file|mimes:doc,DOC,DOCX,docx,PDF,pdf|max:5120',
            ]);
        }
        if ($element->files->count()>0){
            $this->deletOldFiles($element, $fileType);
        }
         $element->files()->create(
                    ['url' =>'/'.$fileType.'/'.$unique.$name,
                        'type' => $fileType]
        );
        $files->move(public_path('/'.$fileType),$unique.$name);
    }
    public function storAppSettings($request){
        $setting = Setting::first();
        if (!$setting){
            $setting = new Setting;
        }
        $setting->appName = $request->input('appName');
        $setting->companyName = $request->input('companyName');
        $setting->email = $request->input('email');
        $setting->phone =  $request->input('phone');
        $setting->facebook =  $request->input('facebook');
        $setting->instagram =  $request->input('instagram');
        $setting->twitter =  $request->input('twitter');
        $setting->youtube =  $request->input('youtube');
        $setting->linkedin =  $request->input('linkedin');
        $setting->maps =  $request->input('maps');
        $setting->location =  $request->input('location');
        $setting->aboutDescription =  $request->input('aboutDescription');
        $setting->save();
        if($request->hasFile('appLogo')){
            $this->storeOneFile($request,$setting,'appLogo');
        }
        return $this->refactorSettings($setting) ;
    }
}