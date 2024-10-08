<?php

namespace App\Http\Controllers;
use App\Models\Email;
use App\Traits\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    use Store;
    public function store(Request $request)
    {
        $countEmail = Email::all()->count();
        $ip=$request->headers->get('Accept-For');
        $countRequest = Email::where('ip',$ip)->count();

        if($countRequest > 5){
            return response()->json(['message' => 'You have reached the limit of sending requests!'], 403);
        }
        if($countEmail > 100){
            $firstEmail = Email::first();
            $firstEmail->delete();
        }
        $request->validate([
            'fullName' => 'required',
            'message' => 'required',
            'subject' => 'required',
            'email' => 'required|email'
        ]);
        $subject = $request->subject;
        $message = $request->message;

        $email = new Email();
        $email->fullName = $request->fullName;
        $email->email = $request->email;
        $email->subject = $subject;
        $email->message = $message;
        $email->ip = $ip;
        $email->save();
    }
    public function response(Request $request){
        $request->validate([
            'email_id' => 'required|exists:emails,id',
            'email' => 'required|email',
            'message' => 'required',
            'subject' => 'required',
        ]);
        $email = Email::find($request->email_id);
        if (!$email) {
            return response()->json(['message' => 'Email not found!'], 404);
        }
        $email->isReplied = 'true';
        $email->save();
        $data = [
            'to'=> $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];
        $this->sendEmail($data);
        }
    public function destroy($id){
        $email = Email::find($id);
        if (!$email) {
            return response()->json(['message' => 'cannot delete undefined email!'], 404);
        }
        $email->delete();
        return response()->json(['message' => 'Email de stage supprimée avec succès']);
    }
}
