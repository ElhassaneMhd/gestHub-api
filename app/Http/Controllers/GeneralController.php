<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Email;
use App\Models\Intern;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Profile;
use App\Models\Session;
use App\Models\Setting;
use App\Models\User;
use App\Traits\Delete;
use App\Traits\Get;
use App\Traits\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    use Get, Store, Delete;
    public function __construct()
    {
        $this->middleware('role:admin|super-admin')->only(['setAppSettings', 'getAcceptedUsers', 'storeNewIntern']);
    }
    public function index(Request $request, $data)
    {
        return $this->GetAll($data,);
    }
    public function show($data, $id)
    {
        return $this->GetByDataId($data, $id);
    }
    public function getStats()
    {
        return $this->getAllStats();
    }
    public function getCount()
    {
        return $this->getAllCount();
    }
    public function setAppSettings(Request $request)
    {
        return response()->json($this->storAppSettings($request));
    }
    public function getSettings()
    {
        $settings = Setting::first();
        if ($settings) {
            return $this->refactorSettings($settings);
        }
    }
    public function getAcceptedUsers()
    {
        return $this->getAllAcceptedUsers();
    }
    public function cities()
    {
        $cities = Offer::all()->pluck('city')->values()->toArray();
        return array_values(array_unique($cities));
    }

    public function sectors()
    {
        $sectors = Offer::all()->pluck('sector')->values()->toArray();
        return array_values(array_unique($sectors));
    }

    public function multipleActions(Request $request, $data, $action)
    {
        $ids = $request['ids'] ?? null;
        if (in_array($data, ['supervisors', 'interns', 'admins', 'users']) && $action === 'delete') {
            DB::beginTransaction();
            foreach ($ids as $id) {
                if (!$profile = Profile::find($id)) {
                    DB::rollBack();
                    return response()->json(['message' => 'profile non trouvé'], 404);
                }
                $this->deleteProfile($profile);
            }
            DB::commit();
            return response()->json(['message' => count($ids) . ' profiles deleted succefully'], 200);
        }
        if ($data == "applications" && in_array($action, ['approve', 'reject'])) {
            DB::beginTransaction();
            foreach ($ids as $id) {
                if (!$application = Application::find($id)) {
                    DB::rollBack();
                    return response()->json(['message' => 'application non trouvé'], 404);
                }
                $this->processApplication($application, $action);
            }
            DB::commit();
            return response()->json(['message' => count($ids) . 'applications processed succefully'], 200);
        }
        if ($data == "applications" && $action == 'delete') {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $application = Application::find($id);
                if (!$application) {
                    DB::rollBack();
                    return response()->json(['message' => 'cannot delete undefined application!'], 404);
                }
                $this->deletOldFiles($application, 'applicationeStage');
                $application->delete();
            }
            DB::commit();
            return response()->json(['message' => count($ids) . 'applications deleted succefully'], 200);
        }
        if ($data == "emails" && $action == 'delete') {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $email = Email::find($id);
                if (!$email) {
                    DB::rollBack();
                    return response()->json(['message' => 'cannot delete undefined email!'], 404);
                }
                $email->delete();
            }
            DB::commit();
            return response()->json(['message' => count($ids) . 'emails deleted succefully'], 200);
        }
        if ($data == "sessions" && in_array($action, ['delete', 'abort'])) {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $session = Session::find($id);
                if (!$session) {
                    DB::rollBack();
                    return response()->json(['message' => 'cannot delete undefined session!'], 404);
                }
                $this->updateSession($session);
                if ($action === 'delete') {
                    $session->delete();
                }
            }
            DB::commit();
            return response()->json(['message' => count($ids) . 'sessions deleted succefully'], 200);
        }
        if ($data == "attestations" && $action == 'generate') {
            DB::beginTransaction();
            foreach ($ids as $id) {
                if (!Intern::find($id)) {
                    DB::rollBack();
                    return response()->json(['message' => 'intern non trouvé'], 404);
                }
                $this->generateAttestation($id);
            }
            DB::commit();
            return response()->json(['message' => count($ids) . ' attestations generated succefully'], 200);
        }
        if ($data == 'users' && $action === 'accept') {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $user = User::find($id);
                if (!$user) {
                    DB::rollBack();
                    return response()->json(['message' => 'user non trouvé'], 404);
                }
                $this->storInternFromUser($user);
            }
            DB::commit();
            return response()->json(['message' => count($ids) . ' interns stored successfully'], 200);
        }
        if ($data == 'notifications' && $action === 'read') {
            $id = auth()->user()->id;
            $profile = Profile::find($id);
            $notifications = $profile->notifications;
            foreach ($notifications as $notification) {
                $notification->isRead = 'true';
                $notification->save();
            }
        }
    }
    public function markNotificationAsRead($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['message' => ' undefined notification!'], 404);
        }
        $notification->isRead = 'true';
        $notification->save();
        return response()->json(['message' => 'notification is readed now'], 200);
    }
    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        $notification->delete();
    }
}
