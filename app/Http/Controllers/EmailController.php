<?php

namespace App\Http\Controllers;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function store(Request $request)
    {
        $countEmail = Email::all()->count();
        if($countEmail > 30){
            $firstEmail = Email::first();
            $firstEmail->delete();
        }
        $request->validate([
            'fullName' => 'required',
            'message' => 'required',
            'subject' => 'required',
            'email' => 'required|email'
        ]);
        $email = new Email();
        $email->fullName = $request->fullName;
        $email->email = $request->email;
        $email->subject = $request->subject;
        $email->message = $request->message;
        $email->save();
        
        $to = 'walid.zakan@gmail.com';
        Mail::to($to)->send(new \App\Mail\WelcomeMail($request->message,$request->subject));

    }
    public function response(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        Mail::to($request->email)->send(new \App\Mail\Mail());
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
